<?php
include_once ('code.php');

Class Validate {
    function error ($code) {
//        http_response_code($code);
//        return json_encode([
//            "message" => ERROR-.$code
//        ]);
    }

    public function success($code) {
        http_response_code($code);
        return json_encode([
            "message" => $code
        ]);
    }
}