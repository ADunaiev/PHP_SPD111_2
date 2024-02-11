<?php

class TestController {

    public function serve() {
        $method = strtolower( $_SERVER['REQUEST_METHOD'] ); // метод запиту
        $action = "do_{$method}" ;
        // чи визначений у даному об'єкті метод з іменем $action (do_get)
        if(method_exists($this, $action)) {
            // якщо визначений, то викликаємо 
            $this->$action() ; // у PHP $this - обов'язково 
            // !! назву методу можна передати через змінну
            // $this->$action() == $this.do_get()
        }
        else {
            http_response_code(405);
            echo "Method not allowed";
        }
    }

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

    protected function connect_db_or_exit() {
        try {
            return new PDO(
                'mysql:host=localhost;dbname=php_spd_111;charset=utf8mb4', 
                'spd_111_user', 
                'spd_pass', [
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            }
        
        catch (PDOException $ex) {
            http_response_code(500);
            echo "Connection error: " . $ex->getMessage();
            exit;
        }
    }

    protected function do_post() {
        
        echo "Hello from do_post";
    }
}