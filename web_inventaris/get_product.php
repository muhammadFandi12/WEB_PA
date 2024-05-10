<?php
@include 'config.php';

$show_products = $pdo->prepare("SELECT name, price, image FROM products");
$show_products->execute();

$products = array();
if ($show_products->rowCount() > 0) {
    while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $fetch_products;
    }
}

header('Content-Type: application/json');
echo json_encode(array('products' => $products));
?>