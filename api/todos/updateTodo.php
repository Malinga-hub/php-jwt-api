<?php

//jwt
include_once('../../vendor/autoload.php');

use Firebase\JWT\JWT;

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//import classes
include_once('../../server/Database.php');
include_once('../../classes/Todo.php');
include_once('../../utils/Sanitize.php');
include_once('../../utils/Constants.php');

//get db
$conn = new Database();
$db = $conn->connect();

//todo instance
$todo = new Todo($db);

//sanitize instance
$sanitize = new Sanitize($db);

//update todo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //jwt
    if (TOKEN != null) {

        try {

            //jwt
            $decoded_jwt = JWT::decode(TOKEN, SECRET_KEY, ALGO);

            //get json input
            $jsonData = json_decode(file_get_contents('php://input'));

            //check if null
            if (!empty($jsonData->title) && !empty($jsonData->id)) {

                $data['todo'] = array(
                    "id" => $sanitize->sanitize($jsonData->id),
                    "user_id" => $decoded_jwt->data->id,
                    "title" => $sanitize->sanitize($jsonData->title)
                );
                $result = $todo->updateTodo($data['todo']);
                if ($result > 0) {
                    //return response
                    http_response_code(200);
                    echo json_encode(array(
                        "status" => 1,
                        "msg" => "success",
                    ));
                } else {
                    include_once('../../utils/NullUpdate.php');
                }
            } else {
                include_once('../../utils/BadRequest.php');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        include_once('../../utils/Unauthorized.php');
    }

    //update todo

    //return response
} else {
    include_once('../../utils/MethodNotAllowed.php');
}
