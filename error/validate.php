<?php

Class Validate {
    private $error = [
        // Error
        404 => "Not found product.",
        400 => "Unable to create product. Data is incomplete.",
        503 => "Unable to create product."
    ];

    private $success = [
        201 => "Product was created.",
        200 => "Success."
        ];

    /**
     * @param $code
     * @return false|string
     */
    function error ($code) {
        http_response_code($code);

        return json_encode([
            "message" => $this->error[$code]
        ]);
    }

    /**
     * @param $code
     * @return false|string
     */
    public function success($code) {
        http_response_code($code);
        return json_encode([
            "message" => $this->success[$code]
        ]);
    }
}