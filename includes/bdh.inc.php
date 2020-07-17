<?php

$server_name = "localhost";
$bd_user = "root";
$bd_pwd = "1234";
$bd_name = "poyofy";

// variable para establecer la coneccion a la BD
$connection = mysqli_connect($server_name, $bd_user, $bd_pwd, $bd_name);

if (!$connection){
	die("Error al establecer la coneccion!".mysqli_connect_error());
}