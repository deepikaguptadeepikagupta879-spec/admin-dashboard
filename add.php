<?php

require_once "db.php";
require_once "functions.php";
require_once "auth.php";

requireAdmin();

$message="";


$categories=getCategories($pdo);



if($_SERVER["REQUEST_METHOD"]==="POST"){


$name=clean($_POST["name"]);

$brand=clean($_POST["brand"]);

$category_id=(int)$_POST["category_id"];

$processor=clean($_POST["processor"]);

$ram=clean($_POST["ram"]);

$storage=clean($_POST["storage"]);

$graphics=clean($_POST["graphics"]);

$display_size=clean($_POST["display_size"]);

$operating_system=clean($_POST["operating_system"]);

$battery=clean($_POST["battery"]);

$color=clean($_POST["color"]);

$weight=clean($_POST["weight"]);

$warranty=clean($_POST["warranty"]);

$price=(float)$_POST["price"];

$stock=(int)$_POST["stock"];

$description=clean($_POST["description"]);



$image=uploadProductImage($_FILES["image"]);



$stmt=$pdo->prepare("
INSERT INTO products
(
name,
brand,
category_id,
processor,
ram,
storage,
graphics,
display_size,
operating_system,
battery,
color,
weight,
warranty,
price,
stock,
image,
description
)

VALUES
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");



$stmt->execute([

$name,

$brand,

$category_id,

$processor,

$ram,

$storage,

$graphics,

$display_size,

$operating_system,

$battery,

$color,

$weight,

$warranty,

$price,

$stock,

$image,

$description

]);



redirect("admin.php");


}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Laptop | <?= SITE_NAME ?></title>

<link rel="icon" href="<?= FAVICON ?>">

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>


<?php require_once "navbar.php"; ?>


<section class="container">


<div class="section-title">

<h2>Add New Laptop</h2>

<p>Add product details carefully.</p>

</div>


<div class="auth-card">

<form method="POST" enctype="multipart/form-data">

<div class="form-group">

<label>Laptop Name</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>


<div class="form-group">

<label>Brand</label>

<input
type="text"
name="brand"
class="form-control"
required>

</div>


<div class="form-group">

<label>Category</label>

<select
name="category_id"
class="form-control"
required>

<option value="">Select Brand Category</option>

<?php foreach($categories as $category){ ?>

<option value="<?= $category["id"] ?>">

<?= $category["name"] ?>

</option>

<?php } ?>

</select>

</div>


<div class="form-group">

<label>Processor</label>

<input
type="text"
name="processor"
class="form-control">

</div>


<div class="form-group">

<label>RAM</label>

<input
type="text"
name="ram"
class="form-control">

</div>


<div class="form-group">

<label>Storage</label>

<input
type="text"
name="storage"
class="form-control">

</div>


<div class="form-group">

<label>Graphics</label>

<input
type="text"
name="graphics"
class="form-control">

</div>


<div class="form-group">

<label>Display Size</label>

<input
type="text"
name="display_size"
class="form-control">

</div>


<div class="form-group">

<label>Operating System</label>

<input
type="text"
name="operating_system"
class="form-control">

</div>


<div class="form-group">

<label>Battery</label>

<input
type="text"
name="battery"
class="form-control">

</div>


<div class="form-group">

<label>Color</label>

<input
type="text"
name="color"
class="form-control">

</div>


<div class="form-group">

<label>Weight</label>

<input
type="text"
name="weight"
class="form-control">

</div>


<div class="form-group">

<label>Warranty</label>

<input
type="text"
name="warranty"
class="form-control">

</div>


<div class="form-group">

<label>Price</label>

<input
type="number"
name="price"
class="form-control"
required>

</div>


<div class="form-group">

<label>Stock</label>

<input
type="number"
name="stock"
class="form-control"
required>

</div>


<div class="form-group">

<label>Product Image</label>

<input
type="file"
name="image"
class="form-control"
accept="image/*"
onchange="previewImage(this)">

</div>


<div class="form-group">

<img
id="preview"
style="width:180px;height:180px;object-fit:contain;">

</div>


<div class="form-group">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="5"></textarea>

</div>


<button
type="submit"
class="btn btn-primary"
style="width:100%;">

Save Laptop

</button>


</form>

</div>

</section>


<?php require_once "footer.php"; ?>

<div class="form-group">

<label>Laptop Name</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>


<div class="form-group">

<label>Brand</label>

<input
type="text"
name="brand"
class="form-control"
required>

</div>


<div class="form-group">

<label>Category</label>

<select
name="category_id"
class="form-control"
required>

<option value="">Select Brand Category</option>

<?php foreach($categories as $category){ ?>

<option value="<?= $category["id"] ?>">

<?= $category["name"] ?>

</option>

<?php } ?>

</select>

</div>


<div class="form-group">

<label>Processor</label>

<input
type="text"
name="processor"
class="form-control">

</div>


<div class="form-group">

<label>RAM</label>

<input
type="text"
name="ram"
class="form-control">

</div>


<div class="form-group">

<label>Storage</label>

<input
type="text"
name="storage"
class="form-control">

</div>


<div class="form-group">

<label>Graphics</label>

<input
type="text"
name="graphics"
class="form-control">

</div>


<div class="form-group">

<label>Display Size</label>

<input
type="text"
name="display_size"
class="form-control">

</div>


<div class="form-group">

<label>Operating System</label>

<input
type="text"
name="operating_system"
class="form-control">

</div>


<div class="form-group">

<label>Battery</label>

<input
type="text"
name="battery"
class="form-control">

</div>


<div class="form-group">

<label>Color</label>

<input
type="text"
name="color"
class="form-control">

</div>


<div class="form-group">

<label>Weight</label>

<input
type="text"
name="weight"
class="form-control">

</div>


<div class="form-group">

<label>Warranty</label>

<input
type="text"
name="warranty"
class="form-control">

</div>


<div class="form-group">

<label>Price</label>

<input
type="number"
name="price"
class="form-control"
required>

</div>


<div class="form-group">

<label>Stock</label>

<input
type="number"
name="stock"
class="form-control"
required>

</div>


<div class="form-group">

<label>Product Image</label>

<input
type="file"
name="image"
class="form-control"
accept="image/*"
onchange="previewImage(this)">

</div>


<div class="form-group">

<img
id="preview"
style="width:180px;height:180px;object-fit:contain;">

</div>


<div class="form-group">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="5"></textarea>

</div>


<button
type="submit"
class="btn btn-primary"
style="width:100%;">

Save Laptop

</button>


</form>

</div>

</section>


<?php require_once "footer.php"; ?>