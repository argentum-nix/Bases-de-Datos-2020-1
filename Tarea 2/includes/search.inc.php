<?php 
session_start();
if(isset($_POST['search-input'])){
	require 'bdh.inc.php';
	$search = $_POST['search-input'];
	if(empty($search)){
		header('Location: ../search.php');
		exit();
	}
	else{
		header('Location: ../search.php?item='.$search);
	}


}

else{
	header('Location: ../search.php');
	exit();
}
