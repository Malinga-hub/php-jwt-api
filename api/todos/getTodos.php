<?php

//get jwt files
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;

//set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

//import classes
include_once('../../server/Database.php');
include_once('../../classes/Todo.php');
include_once('../../utils/Constants.php');

//get db
$conn = new Database();
$db = $conn->connect();

//create todo instance
$todos = new Todo($db);

//get all todos
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (TOKEN != null) {
        try {

            //decode jwt
            $decoded_jwt = JWT::decode(TOKEN, SECRET_KEY, ALGO);

            //get the records
            $records = $todos->getAllTodos();

            if ($records->num_rows > 0) {
                //array to store records
                $data['todos'] = array();

                while ($todo = $records->fetch_assoc()) {
                    array_push($data['todos'], array(
                        "id" => $todo['id'],
                        "user_id" => $todo['user_id'],
                        "title" => $todo['title'],
                        "created" => date("d-m-y", strtotime($todo['created']))
                    ));
                }

                //return result
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "records" => $records->num_rows,
                    "msg" => "success",
                    "data" => $data['todos']
                ));
            } else {
                include_once('../../utils/NotFound.php');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        include_once('../../utils/Unauthorized.php');
    }
} else {
    include_once('../../utils/MethodNotAllowed.php');
}
