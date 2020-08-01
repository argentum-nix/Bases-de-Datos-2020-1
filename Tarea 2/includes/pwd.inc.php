<?php 
session_start();
if(isset($_POST['change-pwd'])) {
    require 'bdh.inc.php';
    $pwd1 = $_POST['pwd1'];
    $pwd2 = $_POST['pwd2'];

    if(empty($pwd2) || empty($pwd1)) {
    	mysqli_close($connection);
		header("Location: ../change_pass.php?error=emptyfields");
		exit();
	}

	else if($pwd1 != $pwd2) {
		mysqli_close($connection);
		header("Location: ../change_pass.php?error=notequal");
		exit();
	}

	else{
		$sql_query = "SELECT password FROM personas WHERE id_persona=".$_SESSION['id'];
    	$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../change_pass.php?error=sqlerror");
			exit();
		}
		mysqli_stmt_execute($statement);
		$res = mysqli_stmt_get_result($statement);
		$fila = mysqli_fetch_assoc($res);
		$checkpwd1 = password_verify($pwd1, $fila['password']);
		$checkpwd2 = password_verify($pwd2, $fila['password']);

		if($checkpwd2 == true or $checkpwd1 == true) {
			mysqli_close($connection);
			header("Location: ../change_pass.php?error=wrongpwd");
			exit();
		}
		else {
			$cripted_pwd = password_hash($pwd2, PASSWORD_DEFAULT);
			$sql_query = "UPDATE personas SET password=? WHERE id_persona=?";
			$statement = mysqli_stmt_init($connection);
			if (!mysqli_stmt_prepare($statement, $sql_query)){
				header("Location: ../change_pass.php?error=sqlerror");
				exit();
			}
			mysqli_stmt_bind_param($statement, "si", $cripted_pwd, $_SESSION['id']);
			mysqli_stmt_execute($statement);
			header("Location: ../change_pass.php?operation=success");
			exit();
		}
	}
}
else{
	mysqli_close($connection);
	header("Location: ../account.php");
	exit();
}