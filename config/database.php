<?php
include_once ('config.php');

class Database {
    /**
     * @var $db
     */
    private $db;
    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $connect = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USERNAME, PASSWORD);
            $connect->exec("set names utf8");
        } catch(PDOException $exception) {
            die("Connection error: ". $exception->getMessage());
        }

        $this->db = $connect;
    }

    public function getConnect() {
        return $this->db;
    }
}