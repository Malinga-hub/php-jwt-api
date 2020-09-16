<?php

http_response_code(200);
echo json_encode(array(
    "status" => 1,
    "msg" => "No records  changed / affected",
    "data" => null
));
