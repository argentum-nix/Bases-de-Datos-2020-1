<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<title>Poyofy</title>
  	<link rel="stylesheet" href="css/signup.css">
  	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
</head>
  	<div class="signup-container">
		<div class="wrap-signup">
		  	<img src="img/poyofy.png" alt="poyofy" width=250px>
		  	<div class="signup-form-title">
				<h1>Regístrate con tu dirección de email</h1>
		  	</div>
			<div class="wrap-form">
		  		<form action="includes/signup.inc.php" method="post">
				<div class="form-input">
			  		<input type="text" name="username" placeholder="Nombre">
				</div>
				<div class="form-input">
			  		<input type="text" name="pwd" placeholder="Contraseña">
				</div>
				<div class="form-input">
			  		<input type="text" name="mail" placeholder="Email">
				</div>
				<div class="form-input">
					<input type="text" name="mail" placeholder="Confirmar email">
				</div>
		  		</form>
			</div>
			<div class="button1">
				<button type="submit" name="signup-submit">Regístrate</button>
			</div>
			<div class="signup-extras">
				<p>¿Ya tienes una cuenta?</p><a href="#">Iniciar sesión.</a>
			</div>
		</div>
  </div>