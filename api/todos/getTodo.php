<?php

//jwt
include_once('../../vendor/autoload.php');

use Firebase\JWT\JWT;

//headers
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

//import classes
include_once('../../server/Database.php');
include_once('../../classes/Todo.php');
include_once('../../utils/Sanitize.php');
include_once('../../utils/Constants.php');

//get db
$conn = new Database();
$db = $conn->connect();

//sanitize instance
$sanitize = new Sanitize($db);

//create todo instance
$todo = new Todo($db);

//get todo
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (TOKEN != null) {

        try {

            $decoded_jwt = JWT::decode(TOKEN, SECRET_KEY, ALGO);

            $data['todo'] = array(
                "id" => $sanitize->sanitize($_GET['id']),
                "user_id" => $decoded_jwt->data->id
            );

            $record = $todo->getTodo($data['todo']);

            if ($record->num_rows > 0) {
                //todo array
                $data['todo_result'] = array();

                //get todo data
                while ($todo_data = $record->fetch_assoc()) {
                    array_push($data['todo_result'], array(
                        "id" => $todo_data['id'],
                        "user_id" => $todo_data['user_id'],
                        "title" => $todo_data['title']
                    ));
                }

                //return reponse and data
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "records" => $record->num_rows,
                    "msg" => "success",
                    "data" => $data['todo_result']
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
