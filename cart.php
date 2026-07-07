<?php

require_once "db.php";
require_once "functions.php";



if(isset($_GET["action"])){


$action=$_GET["action"];



if($action=="add"){


$id=(int)$_GET["id"];


addToCart($id);


redirect("cart.php");


}



if($action=="remove"){


$id=(int)$_GET["id"];


removeFromCart($id);


redirect("cart.php");


}



if($action=="update"){


$id=(int)$_GET["id"];


$qty=(int)$_GET["qty"];


updateCart($id,$qty);


redirect("cart.php");


}



}




$cartItems=getCartItems($pdo);


$total=cartTotal($pdo);


?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

Cart | <?= SITE_NAME ?>

</title>



<link rel="stylesheet" href="style.css">


<link rel="icon" href="<?= FAVICON ?>">


</head>



<body>



<?php require_once "navbar.php"; ?>



<section class="container">



<div class="section-title">


<h2>

Shopping Cart

</h2>


<p>

Review your selected laptops

</p>


</div>



<div class="cart-box">

<?php if(empty($cartItems)){ ?>


<div style="text-align:center;padding:40px;">


<h3>

Your cart is empty

</h3>


<br>


<a href="index.php" class="btn btn-primary">

Continue Shopping

</a>


</div>


<?php }else{ ?>



<?php foreach($cartItems as $item){ ?>



<div class="cart-item">



<img

src="<?= htmlspecialchars($item["image"]) ?>"

alt="<?= htmlspecialchars($item["name"]) ?>">



<div>


<h3>

<?= htmlspecialchars($item["name"]) ?>

</h3>


<p>

<?= htmlspecialchars($item["brand"]) ?>

</p>


<p class="price">

<?= formatPrice($item["price"]) ?>

</p>


</div>




<div>



<form method="GET">


<input type="hidden" name="action" value="update">


<input type="hidden" name="id" value="<?= $item["id"] ?>">



<input

type="number"

name="qty"

value="<?= $item["quantity"] ?>"

min="1"

class="form-control"

style="width:90px;display:inline-block;">



<button

class="btn btn-primary">

Update

</button>


</form>



<br>



<a

href="cart.php?action=remove&id=<?= $item["id"] ?>"

class="btn btn-danger">

Remove

</a>



</div>


</div>



<?php } ?>



<div class="total-box">


Total Amount:

<?= formatPrice($total) ?>


<br><br>


<a href="checkout.php" class="btn btn-primary">

Proceed To Checkout

</a>



</div>



<?php } ?>



</div>


</section>



<?php require_once "footer.php"; ?>



<script src="script.js"></script>


</body>


</html>