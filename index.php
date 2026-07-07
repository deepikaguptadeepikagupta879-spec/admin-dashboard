<?php

require_once "db.php";
require_once "functions.php";


$search=$_GET["search"] ?? "";


if(!empty($search)){

$products=searchProducts($pdo,$search);

}else{

$products=getProducts($pdo);

}



$categories=getCategories($pdo);


?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

<-- deepikaguptadeepikagupta879@gmail.com -->

<?= SITE_NAME ?>

</title>


<link rel="icon" href="<?= FAVICON ?>">


<link rel="stylesheet" href="style.css">


<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


</head>


<body>


<?php require_once "navbar.php"; ?>



<section class="hero">


<div class="container">


<div class="hero-content">


<h1>

Premium Laptop Store

</h1>


<p>

Find the best Gaming, Business and Professional laptops from top brands.

</p>


<a href="#products" class="btn btn-primary">

Shop Now

</a>


</div>



<div class="hero-image">


<img

src="<?= BANNER ?>"

alt="Laptop Banner">


</div>


</div>


</section>



<section class="container">


<div class="section-title">


<h2>

Laptop Categories

</h2>


<p>

Choose laptop according to your requirement.

</p>


</div>



<div class="product-grid">


<?php foreach($categories as $category){ ?>


<div class="dashboard-card">


<h3>

<?= htmlspecialchars($category["name"]) ?>

</h3>


<a

href="index.php?category=<?= $category["id"] ?>"

class="btn btn-primary">

View

</a>


</div>


<?php } ?>


</div>


</section>

<section 
class="container"
id="products">


<div class="section-title">


<h2>

Latest Laptops

</h2>


<p>

Premium laptops collection

</p>


</div>



<div class="product-grid">


<?php foreach($products as $product){ ?>


<div class="product-card">



<div class="product-image">


<img

src="<?= htmlspecialchars($product["image"]) ?>"

alt="<?= htmlspecialchars($product["name"]) ?>">


</div>




<div class="product-info">



<span class="badge">

<?= htmlspecialchars($product["brand"]) ?>

</span>



<h3>

<?= htmlspecialchars($product["name"]) ?>

</h3>



<ul class="spec-list">


<li>

<i class="fa fa-microchip"></i>

<?= htmlspecialchars($product["processor"]) ?>

</li>



<li>

<i class="fa fa-memory"></i>

<?= htmlspecialchars($product["ram"]) ?>

</li>



<li>

<i class="fa fa-hard-drive"></i>

<?= htmlspecialchars($product["storage"]) ?>

</li>


</ul>



<div class="price">

<?= formatPrice($product["price"]) ?>

</div>



<?php if($product["stock"]>0){ ?>


<p class="stock">

Available

</p>


<a

href="cart.php?action=add&id=<?= $product["id"] ?>"

class="btn btn-primary">


<i class="fa fa-cart-plus"></i>

Add To Cart


</a>


<?php }else{ ?>


<p class="out-stock">

Out Of Stock

</p>


<?php } ?>


</div>



</div>


<?php } ?>


</div>


</section>



<?php require_once "footer.php"; ?>



<script src="script.js"></script>


</body>

</html>