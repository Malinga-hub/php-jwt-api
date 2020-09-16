<?php

http_response_code(400);
echo json_encode(array(
    "status" => 0,
    "msg" => "Bad request. Required fields cannot be null"
));
