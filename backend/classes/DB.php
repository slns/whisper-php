<?php 

class DB {
    public function connect() {
        try {
            $db = new PDO('mysql:host=mysqldb-container;dbname=mysqldb', 'mysqluser', 'mysqlpass');

            return $db;
        } catch (PDOException $e) {
            echo "Error connecting to database: " . $e->getMessage();
        }
    }
}