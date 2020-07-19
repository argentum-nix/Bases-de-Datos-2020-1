<?php 
session_start();
if(isset($_POST['create_song'])) {
    require 'bdh.inc.php';
    $nom = $_POST['songname'];
    $duracion = $_POST['duration'];
    if(empty($nom) || empty($duracion)) {
		header("Location: ../add_song.php?error=emptyfields");
		exit();
	}
	else{
		$sql_query = "INSERT INTO canciones (id_artista, nombre, duracion) VALUES (?, ?, ?)";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../add_song.php?error=sqlerror");
			exit();
		}
		echo $_SESSION['id'];
		mysqli_stmt_bind_param($statement, "isi", $_SESSION["id"], $nom, $duracion);
		mysqli_stmt_execute($statement);
		mysqli_stmt_close($statement);
		mysqli_close($connection);
		header("Location: ../add_song.php?operation=success");
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