<?php

require_once "functions.php";



function requireLogin(){

if(!loggedIn()){

redirect("login.php");

}

}



function requireAdmin(){

if(!loggedIn()){

redirect("login.php");

}



if(userRole()!="admin"){

redirect("index.php");

}

}



function requireCustomer(){

if(!loggedIn()){

redirect("login.php");

}



if(userRole()!="customer"){

redirect("index.php");

}

}



?>