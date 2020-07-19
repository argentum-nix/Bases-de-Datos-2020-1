<?php 
session_start();
if(isset($_POST['create_album'])) {
    require 'bdh.inc.php';
    $nom = $_POST['albumname'];
    $debut = $_POST['year'];
    if(empty($nom) || empty($debut)) {
		header("Location: ../add_album.php?error=emptyfields");
		exit();
	}
	else{
		$sql_query = "INSERT INTO albumes (id_artista, nombre, debut_year) VALUES (?, ?, ?)";
		$statement = mysqli_stmt_init($connection);
		if (!mysqli_stmt_prepare($statement, $sql_query)){
			header("Location: ../add_album.php?error=sqlerror");
			exit();
		}
		echo $_SESSION['id'];
		mysqli_stmt_bind_param($statement, "isi", $_SESSION["id"], $nom, $debut);
		mysqli_stmt_execute($statement);
		mysqli_stmt_close($statement);
		mysqli_close($connection);
		header("Location: ../add_album.php?operation=success");
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