<?php

//jwt
include_once('../../vendor/autoload.php');

use Firebase\JWT\JWT;

//headers
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charsetUTF=8");
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

//create todo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!is_null(TOKEN)) {

        try {
            //get decoded json data
            $decoded_jwt = JWT::decode(TOKEN, SECRET_KEY, ALGO);

            $user_id = $decoded_jwt->data->id;

            //get json body data
            $postData = json_decode(file_get_contents('php://input'));

            //check if fields not null
            if (!empty($postData->title)) {
                //todo data array
                $data['todo'] = array(
                    "title" => $sanitize->sanitize($postData->title),
                    "user_id" => $user_id
                );
                //create todo
                $result = $todo->createTodo($data['todo']);

                if ($result > 0) {
                    //return response
                    http_response_code(200);
                    echo json_encode(array(
                        "status" => 1,
                        "msg" => "success",
                    ));
                } else {
                    include_once('../../utils/ServerError.php');
                }
            } else {
                include_once('../../utils/BadRequest.php');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        include_once('../..//utils/Unauthorized.php');
    }
} else {
    include_once('../../utils/MethodNotAllowed.php');
}
