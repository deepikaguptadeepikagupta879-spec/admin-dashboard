<?php

require_once "functions.php";

?>

<header class="navbar">

<div class="container">


<div class="logo">

<a href="index.php">

<img 
src="<?= LOGO ?>"
alt="<?= SITE_NAME ?>">

<span>

<?= SITE_NAME ?>

</span>

</a>

</div>



<div class="search-box">

<form method="GET" action="index.php">

<input

type="text"

name="search"

class="form-control"

placeholder="Search laptops..."

value="<?= $_GET["search"] ?? "" ?>"

>

<button type="submit" class="btn btn-primary">

<i class="fa fa-search"></i>

</button>

</form>

</div>



<nav class="nav-links">


<a href="index.php">

Home

</a>



<?php if(loggedIn()){ ?>


<a href="cart.php">

<i class="fa fa-shopping-cart"></i>

Cart

<span>

(<?= cartCount() ?>)

</span>

</a>



<?php if(userRole()=="admin"){ ?>


<a href="admin.php">

Admin

</a>


<?php } ?>


<a href="logout.php">

Logout

</a>


<?php }else{ ?>


<a href="login.php">

Login

</a>



<a href="signup.php">

Signup

</a>



<?php } ?>


</nav>


</div>

</header>