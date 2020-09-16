<?php

//require jwt files
require '../../vendor/autoload.php'; // get autoload file
use Firebase\JWT\JWT; //inlcude jwt class under namespace firebase\jwt

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//import classes
include_once('../../server/Database.php');
include_once('../../classes/User.php');
include_once('../../utils/Sanitize.php');

//get db connection
$conn = new Database();
$db = $conn->connect();

//create instances
$user = new User($db);
$sanitize = new Sanitize($db);


//login user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //get json body
    $jsonData = json_decode(file_get_contents('php://input'));

    //set array and sanitize data
    $data['user'] = array(
        "email" => $jsonData->email,
        "password" => $jsonData->password
    );

    //login user
    $result = $user->loginUser($data['user']);

    if ($result != null) {

        //set jwt payload variables
        $issuer = "localhsot"; //issues
        $issuedAt = time(); //issues at -> time
        $notBefore = $issuedAt + 10;
        $expireTime = $issuedAt + (60 * 60) * 60; ///valid for a day
        $audience = "users";
        $data['user_result'] = array(
            "id" => $result['id'],
            "email" => $result['email'],
        );

        //jwt main variables
        $payload_info = array(
            "iss" => $issuer,
            "iat" => $issuedAt,
            "nbf" => $notBefore,
            "exp" => $expireTime,
            "aud" => $audience,
            "token_type" => "bearer",
            "data" => $data['user_result']
        );
        $secret_key = "todo@secure123!";

        //create jwt

        $jwt_token = JWT::encode($payload_info, $secret_key);

        //return response
        http_response_code(200);
        echo  json_encode(array(
            "status" => 1,
            "msg" => "success",
            "token" => $jwt_token
        ));
    } else {
        include_once('../../utils/NotFound.php');
    }

    //return response
} else {
    include_once('../../utils/MethodNotAllowed.php');
}
