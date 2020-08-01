<?php 
session_start();
require 'bdh.inc.php';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
	<title>Poyofy - Poyo para tod@s</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="css/start.css" type="text/css">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
	<div class="main-container">
		<?php 
		if($_SERVER['REQUEST_URI'] == '/T2/account.php' or $_SERVER['REQUEST_URI'] == '/T2/edit_account.php' or $_SERVER['REQUEST_URI'] == '/T2/change_pass.php' ){
			include("includes/sidebar_account.php");
		}
		else{
			 include("includes/sidebar.php");
		}?>

		<div id="main" class="main d-flex flex-column">
			<div class="margin-top"></div>
				<div clas="view-container">



