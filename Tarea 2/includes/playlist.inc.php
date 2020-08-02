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

else if(isset($_POST['delete_playlist'])){
	require 'bdh.inc.php';
	$pid = $_POST['to-delete'];
	$query = mysqli_query($connection, "DELETE FROM playlists WHERE id_playlist=".$pid);
	header("Location: ../index.php?delete_playlist=success");
	exit();
}

else if(isset($_POST['change_playname'])){
	require 'bdh.inc.php';
	$pid = $_POST['to-change'];
	$nuevo_nombre = $_POST['name'];
	$is_current = $_POST['is_cur'];
	$sql_query = "UPDATE playlists SET nombre=?  WHERE id_playlist=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)){
		header("Location: ../playlist_edit.php?id=".$pid."&&cur=".$is_current."&&error=sqlerror");
	exit();
	}
	mysqli_stmt_bind_param($statement, "si", $nuevo_nombre, $pid);
	mysqli_stmt_execute($statement);
	header("Location: ../view_playlist.php?id=".$pid."&&cur=".$is_current."&&change=success");
	exit();
}


else{
	header("Location: ../index.php");
	exit();
}