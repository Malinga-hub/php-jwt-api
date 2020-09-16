<?php

class Todo
{

    //variables
    private $id;
    private $title;
    private $user_id;

    private $conn;
    private $table_name;

    //constructor
    public function __construct($db)
    {
        $this->conn = $db;
        $this->table_name = "todos";
    }

    //get all todos
    public function getAllTodos()
    {

        //query
        $query = "SELECT * FROM  " . $this->table_name;

        //prepare statement
        $preparedStatement = $this->conn->prepare($query);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }

    //get single todo
    public function getTodo($data)
    {
        //set the id
        $this->id = $data['id'];
        $this->user_id = $data['user_id'];

        //query
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=? AND user_id=?";

        //prepare
        $preparedStatement = $this->conn->prepare($query);
        $preparedStatement->bind_param("ii", $this->id, $this->user_id);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }

    //create todo
    public function createTodo($data)
    {

        //set the variables
        $this->title = $data['title'];
        $this->user_id = $data['user_id'];

        //query
        $query = "INSERT INTO " . $this->table_name . "(title,user_id) VALUES(?,?)";

        //prepare 
        $preparedStatement = $this->conn->prepare($query);
        $preparedStatement->bind_param("si", $this->title, $this->user_id);
        $preparedStatement->execute();

        return $preparedStatement->affected_rows;
    }

    //update todo
    public function updateTodo($data)
    {

        //set variables
        $this->id = $data['id'];
        $this->user_id = $data['user_id'];
        $this->title = $data['title'];

        //query
        $query = "UPDATE  " . $this->table_name . " SET title=? WHERE id=? AND user_id=?";

        $preparedStatement = $this->conn->prepare($query);
        $preparedStatement->bind_param("sii", $this->title, $this->id, $this->user_id);
        $preparedStatement->execute();

        return $preparedStatement->affected_rows; //check if any row has been updated
    }

    //delete todo
    public function deleteTodo($data)
    {

        $this->id = $data['id'];

        $query = "DELETE FROM " . $this->table_name . " WHERE id=?";

        $preparedStatement = $this->conn->prepare($query);
        $preparedStatement->bind_param("s", $this->id);
        $preparedStatement->execute();

        return $preparedStatement->affected_rows;
    }
}
