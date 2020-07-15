<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
  	<title>Poyofy - Registración</title>
  	<link rel="stylesheet" href="css/start.css">
  	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
</head>
  	<div class="container">
		<div class="wrap">
		  	<img src="img/poyofy.png" alt="poyofy" width=250px>
		  	<div class="form-title">
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
				<div class="select-signup">
					<select name="tipo_persona">
	    				<option value="0">Oyente</option>
	    				<option value="1">Artista</option>
					</select>
				</div>
				<div class="button1">
					<button type="submit" name="signup-submit">Regístrate</button>
				</div>
				</form>
			</div>
			<div class="extras">
				<p>¿Ya tienes una cuenta?</p><a href="login.php">Iniciar sesión.</a>
			</div>
		</div>
  </div>