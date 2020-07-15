<?php 

if(isset($_POST['signup-submit'])){
	require "bdh.inc.php";

	$nombre_usuario = $_POST['username'];
	$pwd_usuario = $_POST['pwd'];
	$mail_usuario = $_POST['mail'];
	$tipo_usuario = $_POST['tipo_persona'];

	// Revisa si algun campo no fue ingresado por el usuario.
	if(empty($nombre_usuario) || empty($pwd_usuario) || empty($mail_usuario)){
		header("Location: ../signup.php?error=emptyfields");
		exit();

	}
	// Revisa si el mail es correcto
	else if(!filter_var($mail_usuario, FILTER_VALIDATE_EMAIL)){
		header("Location: ../signup.php?error=mailcheckfailed");
		exit();
	}
	// Revisa si mail ya existe en la BD
	else{
		$sql_query = "SELECT mail FROM personas WHERE mail=?";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../signup.php?error=sqlerror1");
			exit();
		}
		else{
			mysqli_stmt_bind_param($statement, "s", $mail_usuario);
			mysqli_stmt_execute($statement);
			mysqli_stmt_store_result($statement);
			$check = mysqli_stmt_num_rows($statement);
			if($check >> 0){
				header("Location: ../signup.php?error=userexists");
				exit();
			}
			else{
				$sql_query = "INSERT INTO personas (nombre, password, mail) VALUES (?, ?, ?)";
				$statement = mysqli_stmt_init($connection);
				if (!mysqli_stmt_prepare($statement, $sql_query)){
					header("Location: ../signup.php?error=sqlerror2");
					exit();
				}
				$cripted_pwd = password_hash($pwd_usuario, PASSWORD_DEFAULT);
				mysqli_stmt_bind_param($statement, "sss", $nombre_usuario, $cripted_pwd, $mail_usuario);
				mysqli_stmt_execute($statement);
				$last_id = mysqli_insert_id($connection);

				// Oyente, usamos la tabla de usuarios.
				if($tipo_usuario == "0"){
					$sql_query = "INSERT INTO usuarios (id_usuario) VALUES (?)";
					$statement = mysqli_stmt_init($connection);
					if (!mysqli_stmt_prepare($statement, $sql_query)){
						header("Location: ../signup.php?error=sqlerror3");
						exit();
					}
					mysqli_stmt_bind_param($statement, "i", $last_id);
					mysqli_stmt_execute($statement);
					header("Location: ../signup.php?success");
					exit();
				}

				// Artista, usamos la tabla de artistas.
				else{
					$sql_query = "INSERT INTO artistas (id_artista) VALUES (?)";
					$statement = mysqli_stmt_init($connection);
					if (!mysqli_stmt_prepare($statement, $sql_query)){
						header("Location: ../signup.php?error=sqlerror4");
						exit();
					}
					mysqli_stmt_bind_param($statement, "i", $last_id);
					mysqli_stmt_execute($statement);
					header("Location: ../signup.php?success");
					exit();
				}
			}
		}
	}
	mysqli_stmt_close($statement);
	mysqli_close($connection);
}
else{
	header("Location: ../signup.php");
	exit();
}

