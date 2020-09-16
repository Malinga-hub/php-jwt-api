<?php

//jwt 
define("SECRET_KEY", "todo@secure123!");
define("ALGO", array('HS256'));

// headers
$headers = getallheaders();
if (isset($headers['Authorization'])) {
    define("TOKEN", $token = explode("Bearer ", $headers['Authorization'])[1]);
} else {
    define("TOKEN", null);
}
