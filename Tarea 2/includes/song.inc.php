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
else if(isset($_POST['delete_song'])){
	require 'bdh.inc.php';
	$cid = $_POST['to-delete'];
	$query = mysqli_query($connection, "DELETE FROM canciones WHERE id_cancion=".$cid);
	header("Location: ../index.php?delete_album=success");
	exit();
}

else if(isset($_POST['change_songname'])){
	require 'bdh.inc.php';
	$cid = $_POST['to-change'];
	$nuevo_nombre = $_POST['name'];
	$is_current = $_POST['is_cur'];
	$sql_query = "UPDATE canciones SET nombre=?  WHERE id_cancion=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)){
		header("Location: ../edit_song.php?id=".$cid."&&cur=".$is_current."&&error=sqlerror");
	exit();
	}
	mysqli_stmt_bind_param($statement, "si", $nuevo_nombre, $cid);
	mysqli_stmt_execute($statement);
	header("Location: ../edit_song.php?id=".$cid."&&cur=".$is_current."&&change=success");
	exit();
}

else if(isset($_POST['change_songlength'])){
	require 'bdh.inc.php';
	$cid = $_POST['to-change'];
	$dur = $_POST['time'];
	$is_current = $_POST['is_cur'];
	$sql_query = "UPDATE canciones SET duracion=?  WHERE id_cancion=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)){
		header("Location: ../edit_song.php?id=".$cid."&&cur=".$is_current."&&error=sqlerror");
	exit();
	}
	mysqli_stmt_bind_param($statement, "ii", $dur, $cid);
	mysqli_stmt_execute($statement);
	header("Location: ../edit_song.php?id=".$cid."&&cur=".$is_current."&&change=success");
	exit();
}


else{
	header("Location: ../index.php");
	exit();
}