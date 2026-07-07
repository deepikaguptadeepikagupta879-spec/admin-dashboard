<?php

require_once "config.php";



function clean($data){

return htmlspecialchars(
trim($data),
ENT_QUOTES,
'UTF-8'
);

}



function redirect($page){

header("Location: ".$page);

exit;

}



function loggedIn(){

return isset($_SESSION["user"]);

}



function loginUser($email,$password,$pdo){


$stmt=$pdo->prepare(

"SELECT * FROM users WHERE email=? LIMIT 1"

);


$stmt->execute([$email]);


$user=$stmt->fetch();



if($user && $password==$user["password"]){


$_SESSION["user"]=[

"id"=>$user["id"],

"name"=>$user["name"],

"email"=>$user["email"],

"role"=>$user["role"]

];


return true;

}


return false;


}



function logoutUser(){

unset($_SESSION["user"]);

session_destroy();

}



function userId(){

return $_SESSION["user"]["id"] ?? 0;

}



function userName(){

return $_SESSION["user"]["name"] ?? "";

}



function userRole(){

return $_SESSION["user"]["role"] ?? "";

}



function formatPrice($price){

return "₹".number_format($price,2);

}

?>

function getCategories($pdo){

$stmt=$pdo->query(

"SELECT * FROM categories ORDER BY name ASC"

);

return $stmt->fetchAll();

}



function getProducts($pdo){

$stmt=$pdo->query(

"SELECT 
products.*,
categories.name AS category_name

FROM products

LEFT JOIN categories

ON products.category_id=categories.id

ORDER BY products.id DESC"

);

return $stmt->fetchAll();

}



function getProductById($pdo,$id){

$stmt=$pdo->prepare(

"SELECT *

FROM products

WHERE id=?

LIMIT 1"

);

$stmt->execute([$id]);

return $stmt->fetch();

}



function searchProducts($pdo,$search){

$stmt=$pdo->prepare(

"SELECT *

FROM products

WHERE name LIKE ?

OR brand LIKE ?

OR processor LIKE ?

ORDER BY id DESC"

);


$value="%".$search."%";


$stmt->execute([

$value,

$value,

$value

]);


return $stmt->fetchAll();

}



function getProductsByCategory($pdo,$category){

$stmt=$pdo->prepare(

"SELECT *

FROM products

WHERE category_id=?

ORDER BY id DESC"

);


$stmt->execute([$category]);


return $stmt->fetchAll();

}



function totalProducts($pdo){

$stmt=$pdo->query(

"SELECT COUNT(*) FROM products"

);


return $stmt->fetchColumn();

}



function totalUsers($pdo){

$stmt=$pdo->query(

"SELECT COUNT(*) FROM users"

);


return $stmt->fetchColumn();

}



function totalOrders($pdo){

$stmt=$pdo->query(

"SELECT COUNT(*) FROM orders"

);


return $stmt->fetchColumn();

}



function totalRevenue($pdo){

$stmt=$pdo->query(

"SELECT SUM(total) FROM orders"

);


return $stmt->fetchColumn() ?? 0;

}

?>

function addToCart($productId){

if(!isset($_SESSION["cart"])){

$_SESSION["cart"]=[];

}


if(isset($_SESSION["cart"][$productId])){

$_SESSION["cart"][$productId]++;

}else{

$_SESSION["cart"][$productId]=1;

}

}



function updateCart($productId,$quantity){

if(isset($_SESSION["cart"][$productId])){

$_SESSION["cart"][$productId]=$quantity;

}

}



function removeFromCart($productId){

if(isset($_SESSION["cart"][$productId])){

unset($_SESSION["cart"][$productId]);

}

}



function cartCount(){

if(!isset($_SESSION["cart"])){

return 0;

}


return array_sum($_SESSION["cart"]);

}



function getCartItems($pdo){

$items=[];


if(empty($_SESSION["cart"])){

return $items;

}


$ids=array_keys($_SESSION["cart"]);


$placeholders=str_repeat("?,",count($ids)-1)."?";


$stmt=$pdo->prepare(

"SELECT *

FROM products

WHERE id IN ($placeholders)"

);


$stmt->execute($ids);


$products=$stmt->fetchAll();



foreach($products as $product){


$product["quantity"]=$_SESSION["cart"][$product["id"]];


$product["subtotal"]=$product["price"]*$product["quantity"];


$items[]=$product;


}


return $items;

}



function cartTotal($pdo){

$total=0;


foreach(getCartItems($pdo) as $item){

$total+=$item["subtotal"];

}


return $total;

}



function clearCart(){

unset($_SESSION["cart"]);

}



function uploadProductImage($file){


if(

isset($file)

&&

$file["error"]==0

){


$extension=pathinfo(

$file["name"],

PATHINFO_EXTENSION

);



$fileName=time().".".$extension;


$path=UPLOAD_PATH.$fileName;



move_uploaded_file(

$file["tmp_name"],

$path

);



return $path;


}


return "";

}



function deleteProductImage($image){

if(

!empty($image)

&&

file_exists($image)

){

unlink($image);

}

}

?>

function placeOrder($pdo,$userId,$address,$paymentMethod){

try{


$items=getCartItems($pdo);


if(empty($items)){

return false;

}



$total=cartTotal($pdo);


$orderNo="ORD".time();



$pdo->beginTransaction();



$stmt=$pdo->prepare(

"INSERT INTO orders

(order_no,user_id,total,payment_method,address)

VALUES(?,?,?,?,?)"

);



$stmt->execute([

$orderNo,

$userId,

$total,

$paymentMethod,

$address

]);



$orderId=$pdo->lastInsertId();



$itemStmt=$pdo->prepare(

"INSERT INTO order_items

(order_id,product_id,quantity,price)

VALUES(?,?,?,?)"

);



foreach($items as $item){


$itemStmt->execute([

$orderId,

$item["id"],

$item["quantity"],

$item["price"]

]);


}



$pdo->commit();



clearCart();



return $orderNo;



}catch(Exception $e){



if($pdo->inTransaction()){

$pdo->rollBack();

}


return false;


}

}



function getOrders($pdo){

$stmt=$pdo->query(

"SELECT 

orders.*,

users.name

FROM orders

JOIN users

ON orders.user_id=users.id

ORDER BY orders.id DESC"

);


return $stmt->fetchAll();

}



function getOrderItems($pdo,$orderId){

$stmt=$pdo->prepare(

"SELECT

order_items.*,

products.name,

products.image

FROM order_items

JOIN products

ON order_items.product_id=products.id

WHERE order_id=?"

);


$stmt->execute([$orderId]);


return $stmt->fetchAll();

}



?>