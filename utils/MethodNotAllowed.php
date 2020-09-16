<?php
http_response_code(405);
echo json_encode(array(
    "status" => 0,
    "msg" => "Method not allowed"

));
