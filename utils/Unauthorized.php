<?php
http_response_code(401);
echo json_encode(array(
    "status" => 0,
    "msg" => "Unauthorized"

));
