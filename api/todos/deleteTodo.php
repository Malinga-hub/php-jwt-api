<?php

//jwt
include_once('../../vendor/autoload.php');

use Firebase\JWT\JWT;

//headers
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

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

//todo instance
$todo = new Todo($db);

//delete todo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //check for token
    if (!is_null(TOKEN)) {

        try {
            //get json data
            $jsonData = json_decode(file_get_contents('php://input'));
            //check if id null
            if (!empty($jsonData->id)) {

                //set data array
                $data['todo'] = array(
                    "id" => $sanitize->sanitize($jsonData->id)
                );

                //delete data
                $result = $todo->deleteTodo($data['todo']);
                //return response
                if ($result > 0) {
                    http_response_code(200);
                    echo json_encode(array(
                        "status" => 1,
                        "msg" => "success"
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
} else {
    include_once('../../utils/MethodNotAllowed.php');
}
