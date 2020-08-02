<?php 
session_start();
if(isset($_POST['change-data'])) {
    require 'bdh.inc.php';
    $mail = $_POST['email'];
    $name = $_POST['name'];

	// cambiar solo nombre
	if(empty($mail) and !empty($name)){
		$sql_query = "UPDATE personas SET nombre=? WHERE id_persona=?";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../edit_account.php?error=sqlerror");
			exit();
		}
		mysqli_stmt_bind_param($statement, "si", $name, $_SESSION['id']);
		mysqli_stmt_execute($statement);
		$_SESSION['nombre'] = $name;
		header("Location: ../edit_account.php?operation=success");
		exit();
	}
	// cambiar solo mail
	if(!empty($mail) and empty($name)){
		// revisar primero si el mail nuevo ya esta registrado
		$check_query = "SELECT * FROM personas WHERE mail=?";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $check_query)){
			header("Location: ../edit_account.php?error=sqlerror");
			exit();
		}
		mysqli_stmt_bind_param($statement, "s", $mail);
		mysqli_stmt_execute($statement);
		mysqli_stmt_store_result($statement);
		$check = mysqli_stmt_num_rows($statement);
		if($check >> 0){
			header("Location: ../edit_account.php?error=mailexists");
			exit();
		}

		$sql_query = "UPDATE personas SET mail=? WHERE id_persona=?";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../edit_account.php?error=sqlerror");
			exit();
		}
		mysqli_stmt_bind_param($statement, "si", $mail, $_SESSION['id']);
		mysqli_stmt_execute($statement);
		header("Location: ../edit_account.php?operation=success");
		exit();
	}
	// cambiar ambos
	else if (!empty($mail) and !empty($name)){
		// revisar primero si el mail nuevo ya esta registrado
		$check_query = "SELECT * FROM personas WHERE mail=?";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $check_query)){
			header("Location: ../edit_account.php?error=sqlerror");
			exit();
		}
		mysqli_stmt_bind_param($statement, "s", $mail);
		mysqli_stmt_execute($statement);
		mysqli_stmt_store_result($statement);
		$check = mysqli_stmt_num_rows($statement);
		if($check >> 0){
			header("Location: ../edit_account.php?error=mailexists");
			exit();
		}

		$sql_query = "UPDATE personas SET nombre=?, mail=? WHERE id_persona=?";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../edit_account.php?error=sqlerror");
			exit();
		}
		mysqli_stmt_bind_param($statement, "ssi", $name, $mail, $_SESSION['id']);
		mysqli_stmt_execute($statement);
		$_SESSION['nombre'] = $name;
		header("Location: ../edit_account.php?operation=success");
		exit();
	}

	else{
		mysqli_close($connection);
		header("Location: ../edit_account.php");
		exit();
	}
}
if(isset($_POST['delete-account'])) {
	require 'bdh.inc.php';
	$query = mysqli_query($connection, "DELETE FROM personas WHERE id_persona=".$_SESSION['id']);
	session_start();
	session_unset();
	session_destroy();
	header("Location: ../login.php");

}
else{
	mysqli_close($connection);
	header("Location: ../account.php");
	exit();
}

