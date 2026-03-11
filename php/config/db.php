<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "estateflow";

$conn = mysqli_connect($host,$user,$password,$database);

if(!$conn){
die("Connection failed");
}

?>