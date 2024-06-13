<?php

if (!class_exists('Database')) {
    class Database
    {
        private $server = '10.0.8.41';
        private $username = 'lucas3';
        private $password = 'lucaslucas';
        private $database = 'galeria';
        private $conn;


        public function __construct()
        {
            try {
                $this->conn = new PDO("mysql:host=$this->server;dbname=$this->database;", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }

        public function getConnection()
        {
            return $this->conn;
        }
    }
}