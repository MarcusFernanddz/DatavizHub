<?php
$host="localhost"; 
$user="root"; 
$pass=""; 
$banco="aula4"; 
$conn=mysqli_connect($host, $user, $pass , $banco); 
mysqli_select_db($conn, $banco); 
?>