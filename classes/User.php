<?php

class User
{

    //variables
    private $id;
    private $username;
    private $email;
    private $password;
    private $created;

    private $conn;
    private $table_name;

    //constructor
    public function __construct($db)
    {
        $this->conn = $db;
        $this->table_name = "users";
    }

    //create user
    public function createUser($data)
    {

        //set variables
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = password_hash($data['password'], PASSWORD_BCRYPT);
        //$this->password = $data['password'];

        //echo $this->username . ' ' . $this->email . ' ' . $this->password;
        //query
        $query = "INSERT INTO " . $this->table_name . "(email,username,password) VALUES(?,?,?) ";

        //prepare the statement
        $preparedStatement = $this->conn->prepare($query);

        //bind the parameters
        $preparedStatement->bind_param("sss",  $this->email, $this->username, $this->password);

        //execute
        $result = $preparedStatement->execute();

        //return boolean result
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    //get user by email
    public function getUserByEmail()
    {
        //get user by email
        $query = "SELECT * FROM users WHERE email=?";
        $preparedStatement = $this->conn->prepare($query);
        $preparedStatement->bind_param("s", $this->email);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }

    //login user
    public function loginUser($data)
    {

        //set variables
        $this->email = $data['email'];
        $this->password = $data['password'];

        //get user by email
        $user = $this->getUserByEmail()->fetch_assoc();

        if (password_verify($this->password, $user['password'])) {
            return $user;
        } else {
            return null;
        }
    }
}
