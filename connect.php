<?php
$host = "<host>";
$user = "<user>";
$password = "<pass>";
$database = "<database>";


$dbConnect = mysqli_connect($host, $user, $password, $database)
or die("Kan niet met de database verbinden");
?>
