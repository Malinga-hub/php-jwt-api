<?php

http_response_code(500);
echo json_encode(array(
    "status" => 0,
    "msg" => "Internal server error.",
));
