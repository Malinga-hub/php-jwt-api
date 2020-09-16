<?php

class Database
{

    //variables
    private $host;
    private $username;
    private $password;
    private $db;

    private $conn;

    //connect function
    public function connect()
    {
        //set the variables
        $this->host = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->db = "todos";

        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db);

        if ($this->conn->connect_errno) {
            echo $this->conn->connect_error;
        } else {
            return $this->conn;
        }
    }
}

/*$test = new Database();
print_r($test->connect());*/