<?php

namespace app\config;

class MysqlDBH implements DatabaseHandler
{

    // Remote Details
    // private $host = "127.0.0.1";
    // private $username = "root_server_hms";
    // private $password = "1hvqjFNv3#3S";
    // private $dbname = "shm_db";
    // private $charset = 'utf8mb4';

    // Local DB Details
    private $username = 'root';
    private $host = 'localhost';
    private $password = "";
    private $dbname = "hms_db";
    private $charset = 'utf8mb4';
    private $connectionString;

    function __construct()
    {
        try {
            $dsn = "mysql:host=$this->host;charset=$this->charset;dbname=$this->dbname";
            $this->connectionString = new \PDO($dsn, $this->username, $this->password);
            // echo "Connected";
        } catch (\Exception $ex) {
            echo ('Unable to access server...');
        }
    }

    function connection()
    {
        return $this->connectionString;
    }
}
