<?php
include_once ('../objects/product.php');
$productObject = new ProductObject('products');

echo $productObject->getProduct();
