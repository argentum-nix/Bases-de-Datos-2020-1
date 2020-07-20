<?php 
include("includes/header.php");
if(isset($_GET['id'])) {
	$album_id = $_GET['id'];
}
else {
	header("Location: index.php");
	exit();
}