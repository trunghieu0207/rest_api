<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once ('../objects/product.php');
$productObject = new ProductObject('products');
$id = isset($_GET['id']) ? $_GET['id'] : '';

echo $productObject->getProduct($id);
