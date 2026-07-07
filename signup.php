<?php

require_once "db.php";
require_once "functions.php";


$error="";

$success="";



if($_SERVER["REQUEST_METHOD"]=="POST"){



$name=clean($_POST["name"]);

$email=clean($_POST["email"]);

$phone=clean($_POST["phone"]);

$password=clean($_POST["password"]);




$check=$pdo->prepare(

"SELECT id FROM users WHERE email=?"

);



$check->execute([$email]);



if($check->fetch()){


$error="Email already registered";


}else{



$stmt=$pdo->prepare(

"INSERT INTO users

(name,email,phone,password,role)

VALUES(?,?,?,?,?)"

);



$stmt->execute([


$name,

$email,

$phone,

$password,

"customer"


]);



$success="Account created successfully. Please login.";


}


}



?>


<!DOCTYPE html>

<html lang="en">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

Signup | <?= SITE_NAME ?>

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

Create Account

</h2>


<p>

Register as customer

</p>


</div>

<?php if($error){ ?>


<p style="color:red;text-align:center;">

<?= $error ?>

</p>


<?php } ?>



<?php if($success){ ?>


<p style="color:green;text-align:center;">

<?= $success ?>

</p>


<?php } ?>




<form method="POST">


<div class="form-group">


<label>

Full Name

</label>


<input

type="text"

name="name"

class="form-control"

placeholder="Enter your name"

required>


</div>




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

Phone Number

</label>


<input

type="text"

name="phone"

class="form-control"

placeholder="Enter phone number"

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

placeholder="Create password"

required>


</div>




<button

type="submit"

class="btn btn-primary"

style="width:100%;">

Create Account

</button>



</form>




<p style="text-align:center;margin-top:20px;">


Already have an account?


<a href="login.php" style="color:#2563eb;">


Login Here


</a>


</p>



</div>


</section>



<?php require_once "footer.php"; ?>



<script src="script.js"></script>


</body>


</html>