<?php

require_once "db.php";
require_once "functions.php";
require_once "auth.php";

requireAdmin();

$totalProducts = totalProducts($pdo);
$totalUsers = totalUsers($pdo);
$totalOrders = totalOrders($pdo);
$totalRevenue = totalRevenue($pdo);

$products = getProducts($pdo);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard | <?= SITE_NAME ?></title>

<link rel="icon" href="<?= FAVICON ?>">

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<?php require_once "navbar.php"; ?>


<section class="container">

<div class="section-title">

<h2>Admin Dashboard</h2>

<p>Manage your laptop store easily.</p>

</div>


<div class="product-grid">


<div class="dashboard-card">

<h3>Total Laptops</h3>

<h2><?= $totalProducts ?></h2>

</div>


<div class="dashboard-card">

<h3>Total Customers</h3>

<h2><?= $totalUsers ?></h2>

</div>


<div class="dashboard-card">

<h3>Total Orders</h3>

<h2><?= $totalOrders ?></h2>

</div>


<div class="dashboard-card">

<h3>Total Sales</h3>

<h2><?= formatPrice($totalRevenue) ?></h2>

</div>


</div>


<div style="margin:30px 0;">

<a href="add.php" class="btn btn-primary">

<i class="fa-solid fa-plus"></i>

Add New Laptop

</a>

</div>


<div class="table-box">

<table>

<thead>

<tr>

<th>ID</th>

<th>Image</th>

<th>Laptop Name</th>

<th>Brand</th>

<th>Processor</th>

<th>Price</th>

<th>Stock</th>

<th>Action</th>

</tr>

</thead>


<tbody>

<?php foreach($products as $product){ ?>

<tr>

<td>

<?= $product["id"] ?>

</td>


<td>

<img
src="<?= $product["image"] ?>"
class="table-img"
alt="<?= htmlspecialchars($product["name"]) ?>">

</td>


<td>

<?= htmlspecialchars($product["name"]) ?>

</td>


<td>

<?= htmlspecialchars($product["brand"]) ?>

</td>


<td>

<?= htmlspecialchars($product["processor"]) ?>

</td>


<td>

<?= formatPrice($product["price"]) ?>

</td>


<td>

<?php if($product["stock"]>0){ ?>

<span class="stock">

<?= $product["stock"] ?>

</span>

<?php }else{ ?>

<span class="out-stock">

Out

</span>

<?php } ?>

</td>


<td>

<a
href="edit.php?id=<?= $product["id"] ?>"
class="btn btn-primary">

<i class="fa-solid fa-pen"></i>

Edit

</a>


<a
href="delete.php?id=<?= $product["id"] ?>"
class="btn btn-danger"
onclick="return confirmDelete();">

<i class="fa-solid fa-trash"></i>

Delete

</a>

</td>


</tr>


<?php } ?>


<?php if(count($products)==0){ ?>

<tr>

<td colspan="8">

No Laptop Available

</td>

</tr>

<?php } ?>


</tbody>

</table>

</div>

</div>

</section>


<?php require_once "footer.php"; ?>


</body>

</html>