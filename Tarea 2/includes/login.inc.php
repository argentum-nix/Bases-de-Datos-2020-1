<?php 

if(isset($_POST['login-submit'])) {
    require 'bdh.inc.php';

    $mail_usuario = $_POST['mail'];
	$pwd_usuario = $_POST['pwd'];

	// Revisa si algun campo no fue ingresado por el usuario.
	if(empty($pwd_usuario) || empty($mail_usuario)) {
		header("Location: ../login.php?error=emptyfields");
		exit();
	}
	// Revision de BD
	// Asumpcion: existiran nombres repetidos de los usuarios pero no los mails.
	else{
		$sql_query = "SELECT * FROM personas WHERE mail=?";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)) {
			header("Location: ../login.php?error=sqlerror");
			exit();
		}
		
		mysqli_stmt_bind_param($statement, "s", $mail_usuario);
		mysqli_stmt_execute($statement);
		$res = mysqli_stmt_get_result($statement);
		$fila = mysqli_fetch_assoc($res);
		if($fila){
			$nom = $fila["nombre"];
			$uid = $fila["id_persona"];
			$checkpwd = password_verify($pwd_usuario, $fila['password']);
			if($checkpwd == true){
				session_start();
				$_SESSION["id"] = $uid;
				$_SESSION["nombre"] = $nom;
				$res= mysqli_query($connection, "SELECT * FROM usuarios WHERE id_usuario='$uid'");
				if(mysqli_num_rows($res) > 0){
					$_SESSION['usertype'] = 'user';
					header("Location: ../index.php?login=success");
					exit();
				}
				else{
					$res = mysqli_query($connection, "SELECT * FROM artistas WHERE id_artista='$uid'");
					if(mysqli_num_rows($res) > 0){
						$_SESSION['usertype'] = 'artist';
						header("Location: ../index.php?login=success");
						exit();
					}
					else{
						header("Location: ../login.php?error=sqlerror");
						exit();
					}
				}
			}

			else if($checkpwd == false){
				header("Location: ../login.php?error=wrongpwd");
				exit();
			}
		}
		else{
			header("Location: ../login.php?error=notfound");
			exit();
		}
	}
	mysqli_stmt_close($statement);
	mysqli_close($connection);
	}

else{
	header("Location: ../login.php");
	exit();
}