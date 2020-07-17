<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<title>Poyofy - Login</title>
  	<link rel="stylesheet" href="css/start.css">
  	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
</head>
	<div class="container">
		<div class="wrap">
			<img src="img/poyofy.png" alt="poyofy" width=250px>
			<form action="includes/login.inc.php" method="post">
				<div class="form-input">
			  		<input type="text" name="mail" placeholder="Email">
				</div>
				<div class="form-input">
			  		<input type="text" name="pwd" placeholder="Contraseña">
				</div>
				<div class="button1">
					<button type="submit" name="login-submit">Iniciar Sesión</button>
				</div>
			</form>
			<?php 
			if (isset($_GET["error"])) {
				if ($_GET['error'] == 'emptyfields') {
					echo '<div class="error">Por favor, rellene todos los campos.</div>';
				}
				else if ($_GET["error"] == 'wrongpwd') {
					echo '<div class="error">Contraseña incorrecta.</div>';
				}
				else if ($_GET["error"] == 'notfound') {
					echo '<div class="error">Usuario no encontrado en el sistema.</div>';
				}
			}
			?>
			<div class="extras">
				<a href="signup.php">Regístrate.</a>
			</div>
		</div>
	</div>