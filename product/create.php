<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

include_once ('../objects/product.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"));
$productObject = new ProductObject('products');
echo $productObject->createProduct($data);