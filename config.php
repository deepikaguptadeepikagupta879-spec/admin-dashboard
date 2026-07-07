<?php

define("SITE_NAME","Laptop Store");

define("SITE_URL","http://localhost/laptop_store/");


define("DB_HOST","localhost");

define("DB_NAME","laptop_store");

define("DB_USER","root");

define("DB_PASS","");



define(
"UPLOAD_PATH",
"uploads/products/"
);


define(
"LOGO",
"uploads/branding/logo.png"
);


define(
"BANNER",
"uploads/banners/banner.jpg"
);


define(
"FAVICON",
"uploads/branding/favicon.ico"
);



date_default_timezone_set("Asia/Kolkata");


if(session_status()===PHP_SESSION_NONE){

session_start();

}

?>