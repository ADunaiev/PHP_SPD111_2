<?php

include_once "ApiController.php";

class AuthController extends ApiController {

    protected function do_get() {
        $db = $this->connect_db_or_exit();
        // виконання запитів
        $sql = "CREATE TABLE IF NOT EXISTS Users (
            `id`          CHAR(36)        PRIMARY KEY DEFAULT (UUID()),
            `email`       VARCHAR(128)    NOT NULL,
            `name`        VARCHAR(64)     NOT NULL,
            `password`    CHAR(32)        NOT NULL COMMENT 'Hash of password', 
            `avatar`      VARCHAR(128)    NULL
        )ENGINE = INNODB, DEFAULT CHARSET = utf8mb4";
        try {
            $db->query($sql);
        }
        catch(PDOException $ex) {
            http_response_code(500);
            echo "Connection error: " . $ex->getMessage();
            exit;
        }
        echo "Hello from do_get - Query OK"; 
    }

    /* автентифікація користувача */

    protected function do_patch() {
        $result = [ // REST - як шаблон 
            'status' => 0,
            'meta' => [
                'api' => 'auth',
                'service' => 'authentication',
                'time' => time()
            ],
            'data' => [
                'message' => $_GET
            ],
        ];

        if(empty($_GET['email'])) {
            $result['data']['message'] = "Missing required parameter: 'email'";
            $this->end_with($result);
        }
        $email = trim($_GET['email']);

        if(empty($_GET['password'])) {
            $result['data']['message'] = "Missing required parameter: 'password'";
            $this->end_with($result);
        }
        $password = $_GET['password'];

        $sql = "SELECT * FROM users u WHERE u.email = ? AND u.password = ?";
        $db = $this->connect_db_or_exit();
        try {
            $prep = $db->prepare($sql);
            // запис виконюється з передачею параметрів
            $prep->execute([
                $email,
                md5($password)
            ]);
            $res = $prep->fetch();
            //$result['data']['message'] = var_export( $res, true);

            if ($res === false) {
                $result['data']['message'] = "Credentials rejected!";
                $this->end_with($result);
            }


        }
        catch(PDOException $ex) {
            http_response_code(500);
            echo "Connection error: " . $ex->getMessage();
            exit;
        }
        // робота з сесіями
        session_start();
        $_SESSION['user'] = $res;
        $_SESSION['auth-moment'] = time();

        $result['status'] = 1;
        $this->end_with( $result );
    }

    /**
     * Реєстрація нового користувача (Create)
     */

    protected function do_post() {
        /*
        $result = [
            'get' => $_GET,
            'post' => $_POST,
            'files' => $_FILES,
        ];*/
        $result = [ // REST - як шаблон 
            'status' => 0,
            'meta' => [
                'api' => 'auth',
                'service' => 'signup',
                'time' => time()
            ],
            'data' => [
                'message' => ""
            ],
        ];
        if(empty($_POST['user-name'])) {
            $result['data']['message'] = "Missing required parameter: 'user-name'";
            $this->end_with($result);
        }
        $user_name = trim($_POST['user-name']);
        if(empty($_POST['user-email'])) {
            $result['data']['message'] = "Missing required parameter: 'user-email'";
            $this->end_with($result);
        }
        $user_email = trim($_POST['user-email']);

        if(empty($_POST['user-password'])) {
            $result['data']['message'] = "Missing required parameter: 'user-password'";
            $this->end_with($result);
        }
        $user_password = $_POST['user-password'];

        if(strlen($user_name) < 2) {
            $result['data']['message'] = "Validation violation: 'user-name' is too short";
            $this->end_with($result);
        }
        if( preg_match('/\d/', $user_name)) {
            $result['data']['message'] = 
                "Validation violation: 'user-name' must not contain digit(s)";
            $this->end_with($result);
        }

        if(empty($_POST['user-name'])) {
            $result['data']['message'] = "Missing required parameter: 'user-name'";
        }
        $filename = "";
        if(! empty($_FILES['user-avatar'])){
            // файл опціональний, але якщо переданий, то перевіряємо його
            if (
                $_FILES['user-avatar']['error'] != 0 || 
                $_FILES['user-avatar']['size'] == 0
            ){
                $result['data']['message'] = "File upload error";
                $this->end_with($result);
            } 
            // перевіряємо тип файлу (розширення) на перелік допустимих
            $ext = pathinfo($_FILES['user-avatar']['name'], PATHINFO_EXTENSION);
            
            if (strpos(".png.jpg.bmp", $ext) === false) {
                $result['data']['message'] = "File type error";
                $this->end_with($result);
            }

            // генеруємо іи'я для збереження, залишаємо розширення
            do {
                $filename = uniqid(). "." . $ext;
            } // перевіряємо чи не потрапили в існуючий файл 
            while (is_file("./wwwroot/avatar/" . $filename)) ;

            // переносимо завантаженний файл до нового розміщення
            move_uploaded_file(
                $_FILES['user-avatar']['tmp_name'], 
                "./wwwroot/avatar/" . $filename
            );
        }
        /* Запити до бд поділяються на 2 типи: звичайні та підготовлені. 
        У звичайних запитах дані підставляються у текст запиту. У підготовлених 
        ідуть окремо. Звичайні запити виконуються за один акт комунікації бд та php.
        Підготовлені щонайменьше за два. Перший запит готує, другий передає дані. 
        Хоча підготовлені запити призначені для повторного (багаторазового) використання, 
        вони мають значно кращі параметри безпеки щодо "SQL-інєкцій.
        Тому їх використання (підготовленних запитів) рекомендується у всіх випадках, 
        коли у запит додаються дані, що приходять від користувача (зовні). 
        "*/

        $db = $this->connect_db_or_exit();
        // виконання запитів
        // у запиті залишаються placeholders - знаки "?"
        $sql = "INSERT INTO users (`email`, `name`, `password`, `avatar`)
                VALUES(?,?,?,?)";
        try {
            $prep = $db->prepare($sql);
            // запис виконюється з передачею параметрів
            $prep->execute([
                $user_email,
                $user_name,
                md5($user_password),
                $filename
            ]);
        }
        catch(PDOException $ex) {
            http_response_code(500);
            echo "Connection error: " . $ex->getMessage();
            exit;
        }

        $result['status'] = 1;
        $result['data']['message'] = "Signup OK";
        $this->end_with($result);
        
    }
}

/*
CRUD - повнота -- реалізація щонайменше 4-х операцій з даними
C Create    POST
R Read      GET
U Update    PUT
D Delete    DELETE
&"C:\Program Files\Git\usr\bin\openssl" pkcs12 -in cert.pfx -clcerts -nokeys -out cert.cer
	SSI Engin on
	SSLCertificateFile "C:/Users/ADunaev/source/repos/PHP_SPD111/cert.cer"
	SSLCertificateKeyFile "C:/Users/ADunaev/source/repos/PHP_SPD111/cert.key"

    під адміном входимо в Термінал
     New-SelfSignedCertificate -CertStoreLocation Cert:\LocalMachine\My -DnsName "spd-111.loc", "www.spd-111.loc" -FriendlyName "spd111LocCert" -NotAfter (Get-Date).AddYears(100)
    далі йдемо в certlm.msc
    перетягуємо сертіфікат в довірені
    ПК миши та усі завдання + експорт отримуємо перший файл
     &"C:\Program Files\Git\usr\bin\openssl" pkcs12 -in cert.pfx -clcerts -nokeys -out cert.cer
     &"C:\Program Files\Git\usr\bin\openssl" pkcs12 -in cert.pfx -nocerts -nodes -out cert.key

    в vhost xampp додаємо налаштування:
    SSLEngine on
	SSLCertificateFile "C:/Users/ADunaev/source/repos/PHP_SPD111/cert.cer"
	SSLCertificateKeyFile "C:/Users/ADunaev/source/repos/PHP_SPD111/cert.key"
*/