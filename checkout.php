<?php

require_once "db.php";
require_once "functions.php";
require_once "auth.php";


requireLogin();



$items=getCartItems($pdo);



if(empty($items)){


redirect("cart.php");


}



$total=cartTotal($pdo);


$message="";



if($_SERVER["REQUEST_METHOD"]=="POST"){



$address=clean($_POST["address"]);


$payment=clean($_POST["payment_method"]);




if(empty($address) || empty($payment)){


$message="Please fill all details";


}else{



$order=placeOrder(

$pdo,

userId(),

$address,

$payment

);



if($order){


$message="Order placed successfully. Order ID : ".$order;



}else{


$message="Order failed. Try again.";


}



}



}



?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

Checkout | <?= SITE_NAME ?>

</title>



<link rel="stylesheet" href="style.css">


<link rel="icon" href="<?= FAVICON ?>">


</head>



<body>



<?php require_once "navbar.php"; ?>



<section class="container">


<div class="section-title">


<h2>

Checkout

</h2>


<p>

Complete your order

</p>


</div>



<div class="auth-card">



<?php if($message){ ?>


<p style="color:green;text-align:center;">

<?= $message ?>

</p>


<?php } ?>



<form method="POST">

<div class="form-group">


<label>

Shipping Address

</label>



<textarea

name="address"

class="form-control"

rows="5"

placeholder="Enter complete address"

required></textarea>


</div>




<div class="form-group">


<label>

Payment Method

</label>



<select

name="payment_method"

class="form-control"

required>


<option value="">

Select Payment Method

</option>



<option value="Cash on Delivery">

Cash on Delivery

</option>



<option value="UPI">

UPI Payment

</option>



<option value="Debit Card">

Debit Card

</option>



<option value="Credit Card">

Credit Card

</option>


</select>


</div>



<h3 style="margin:25px 0;">

Order Summary

</h3>



<?php foreach($items as $item){ ?>


<div style="display:flex;justify-content:space-between;margin-bottom:10px;">


<span>

<?= htmlspecialchars($item["name"]) ?>

×

<?= $item["quantity"] ?>

</span>



<span>

<?= formatPrice($item["subtotal"]) ?>

</span>


</div>


<?php } ?>



<hr>


<h3 style="margin:20px 0;">


Total:

<?= formatPrice($total) ?>


</h3>




<button

type="submit"

class="btn btn-primary"

style="width:100%;">

Place Order

</button>




</form>



</div>


</section>



<?php require_once "footer.php"; ?>



<script src="script.js"></script>


</body>


</html>