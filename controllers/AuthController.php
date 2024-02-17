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
        $result = [
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

        $result['data']['status'] = 1;
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