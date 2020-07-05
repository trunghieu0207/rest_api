<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once ('../objects/product.php');
$productObject = new ProductObject('products');
$id = isset($_GET['id']) ? $_GET['id'] : '';

echo $productObject->getProduct($id);
