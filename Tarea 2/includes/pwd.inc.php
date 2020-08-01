<?php 
session_start();
if(isset($_POST['change-pwd'])) {
    require 'bdh.inc.php';
    $pwd1 = $_POST['pwd1'];
    $debut = $_POST['pwd2'];

    
  	$sql_query = "SELECT password FROM personas WHERE id_persona=".$_SESSION['id'];
    $statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../change_pass.php?error=sqlerror");
			exit();
		}
   	mysqli_stmt_bind_param($statement, "isi", $_SESSION["id"], $nom, $debut);
		mysqli_stmt_execute($statement);
		mysqli_stmt_close($statement);
		mysqli_close($connection);
		header("Location: ../change_pass.php?operation=success");
		exit();
	}


    if(empty($nom) || empty($debut)) {
		header("Location: ../change_pass.php?error=emptyfields");
		exit();
	}
	else if($old == $pwd1 and $old==$pwd2){
		header("Location: ../change_pass.php?error=samepass");
		exit();
	}
	else if($pwd1 != $pwd2){
		header("Location: ../change_pass.php?error=notequal");
		exit();
	}
	else{

	}
}
else{
	header("Location: ../account.php");
	exit();
}