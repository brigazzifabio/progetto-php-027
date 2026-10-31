<?php
//connessioni MYSQL
$host = "localhost";  //127.0.0.1
$port = 3306;
$username = "root";
$password = "";
$database = "progettophp3";

$conn = mysqli_connect($host,$username,$password,$database,$port) or die("errore di connessione a mysql");
?>