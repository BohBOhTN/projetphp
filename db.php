<?php

class Database {
    private $host = 'ec2-51-44-182-46.eu-west-3.compute.amazonaws.com';
    private $db_name = 'projet_db';
    private $username = 'admin_app';
    private $password = 'Easy*123';
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}