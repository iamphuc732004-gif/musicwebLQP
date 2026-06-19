<?php

class Database {
    private $host = "localhost";
    private $dbname = "musicweb";
    private $username = "root";
    private $password = "";

    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->dbname
            );

            if ($this->conn->connect_error) {
                die("Kết nối thất bại: " . $this->conn->connect_error);
            }

            $this->conn->set_charset("utf8");

        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }

        return $this->conn;
    }
}