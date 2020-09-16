<?php

http_response_code(404);
echo json_encode(array(
    "status" => 0,
    "records" => 0,
    "msg" => "Not found",
    "data" => null
));
