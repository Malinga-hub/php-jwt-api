<?php

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//import classess
include_once('../../server/Database.php');
include_once('../../classes/User.php');
include_once('../../utils/Sanitize.php');

//get db connection
$conn = new Database();
$db = $conn->connect();

//create user instance
$user = new User($db);

//create senitize instance
$sanitize = new Sanitize($db);


//create user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //get json data
    $jsonData = json_decode(file_get_contents('php://input'));

    //set array and clean data
    $data['user'] = array(
        "username" => $sanitize->sanitize($jsonData->username),
        "email" => $sanitize->sanitize($jsonData->email),
        "password" => $sanitize->sanitize($jsonData->password)
    );

    //create user
    $result = $user->createUser($data['user']);

    //return response
    if ($result) {

        http_response_code(200);
        echo json_encode(array(
            "status" => 1,
            "msg" => "success"
        ));
    } else {
        include_once('../../utils/ServerError.php');
    }
} else {
    include_once('../../utils/MethodNotAllowed.php');
}
