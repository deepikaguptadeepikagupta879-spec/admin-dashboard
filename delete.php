<?php

require_once "db.php";
require_once "functions.php";
require_once "auth.php";

requireAdmin();


$id=(int)($_GET["id"] ?? 0);


if($id>0){


$product=getProductById($pdo,$id);


if($product){


deleteProductImage($product["image"]);



$stmt=$pdo->prepare("
DELETE FROM products
WHERE id=?
");


$stmt->execute([$id]);


}


}


redirect("admin.php");

?>