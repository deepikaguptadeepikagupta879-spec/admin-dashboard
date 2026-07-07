<?php

require_once "db.php";
require_once "functions.php";


$error="";


if($_SERVER["REQUEST_METHOD"]=="POST"){


$email=clean($_POST["email"]);

$password=clean($_POST["password"]);



if(loginUser($email,$password,$pdo)){



if(userRole()=="admin"){


redirect("admin.php");


}else{


redirect("index.php");


}



}else{


$error="Invalid email or password";


}


}



?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

Login | <?= SITE_NAME ?>

</title>



<link rel="stylesheet" href="style.css">


<link rel="icon" href="<?= FAVICON ?>">


</head>



<body>



<?php require_once "navbar.php"; ?>



<section class="container">


<div class="auth-card">



<div class="section-title">


<h2>

Login

</h2>


<p>

Access your account

</p>


</div>



<?php if($error){ ?>


<p style="color:red;text-align:center;">

<?= $error ?>

</p>


<?php } ?>



<form method="POST">

<div class="form-group">


<label>

Email Address

</label>


<input

type="email"

name="email"

class="form-control"

placeholder="Enter your email"

required>


</div>




<div class="form-group">


<label>

Password

</label>


<input

type="password"

name="password"

class="form-control"

placeholder="Enter your password"

required>


</div>




<button

type="submit"

class="btn btn-primary"

style="width:100%;">

Login

</button>



</form>



<p style="text-align:center;margin-top:20px;">


Don't have an account?


<a href="signup.php" style="color:#2563eb;">


Create Account


</a>


</p>



</div>


</section>



<?php require_once "footer.php"; ?>



<script src="script.js"></script>


</body>


</html>