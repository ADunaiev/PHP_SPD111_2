<?php
include_once "ApiController.php";

class TestController extends ApiController {

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

    protected function do_post() {
        $db = $this->connect_db_or_exit();

        // виконання запитів
        $sql = "CREATE TABLE IF NOT EXISTS UserRegistration (
            `id`          CHAR(36)        PRIMARY KEY DEFAULT (UUID()),
            `date-time`   DATETIME        NOT NULL,
            `user-id`     CHAR(36)        NOT NULL,
            `token`       CHAR(32)        NOT NULL COMMENT 'Hash of password',
            CONSTRAINT `fk_userregistration_user`
                FOREIGN KEY (`user-id`) REFERENCES Users (`id`)
                ON DELETE CASCADE
                ON UPDATE RESTRICT
        )ENGINE = INNODB, DEFAULT CHARSET = utf8mb4";
        try {
            $db->query($sql);
        }
        catch(PDOException $ex) {
            http_response_code(500);
            echo "Connection error: " . $ex->getMessage();
            exit;
        }
        echo "Hello from do_post - Query OK"; 
    }
}