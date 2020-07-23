<?php 
session_start();
if(isset($_POST['search-input'])){
	require 'bdh.inc.php';
	$search = $_POST['search-input'];
	if(empty($search) {
		header('Location: ../search.php');
		exit();
	}
	else{
		$search_songs
		$search_albums
		$search_users
		$search_artist
	}


}

else{
	header('Location: ../search.php');
	exit();
}
