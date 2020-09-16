<?php

class Sanitize
{

    //variables
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function sanitize($data)
    {
        return htmlentities(strip_tags(mysqli_real_escape_string($this->conn, $data)));
    }
}
