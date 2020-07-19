<?php 
session_start();
if(isset($_POST['create_playlist'])) {
    require 'bdh.inc.php';
    $nom = $_POST['playname'];
    if(empty($nom)) {
		header("Location: ../add_playlist.php?error=emptyfields");
		exit();
	}
	else{
		$sql_query = "INSERT INTO playlists (id_usuario, nombre) VALUES (?, ?)";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../add_playlist.php?error=sqlerror");
			exit();
		}
		echo $_SESSION['id'];
		mysqli_stmt_bind_param($statement, "is", $_SESSION["id"], $nom);
		mysqli_stmt_execute($statement);
		mysqli_stmt_close($statement);
		mysqli_close($connection);
		header("Location: ../add_playlist.php?operation=success");
		exit();
	}
}
else if(isset($_POST['cancel_creation'])){
	header("Location: ../index.php");
	exit();
}
	

else{
	header("Location: ../index.php");
	exit();
}