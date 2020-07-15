<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Poyofy</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<header>
		<nav>
			<img src="img/poyofy.png" alt="poyofy">
			<div>
				<form action="includes/login.inc.php" method="post">
					<input type="text" name="mailuid" placeholder="Correo electrónico">
					<input type="password" name="pwd" placeholder="Contraseña">
					<button type="submit" name="login-submit">Iniciar sesión</button>
				</form>
				<a href="signup.php">Registrarse</a>
				<form action="includes/logout.inc.php" method="post">
					<button type="submit" name="logout-submit">Cerrar sesión</button>
				</form>
			</div>
		</nav>
	</header>

</body>
</html>