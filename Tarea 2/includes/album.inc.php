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

else if(isset($_POST['delete_album'])){
	require 'bdh.inc.php';
	$alid = $_POST['to-delete'];
	$query = mysqli_query($connection, "DELETE FROM albumes WHERE id_album=".$alid);
	header("Location: ../index.php?delete_album=success");
	exit();
}

else if(isset($_POST['change_albumname'])){
	require 'bdh.inc.php';
	$alid = $_POST['to-change'];
	$nuevo_nombre = $_POST['name'];
	$is_current = $_POST['is_cur'];
	$sql_query = "UPDATE albumes SET nombre=?  WHERE id_album=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)){
		header("Location: ../album_edit.php?id=".$alid."&&cur=".$is_current."&&error=sqlerror");
	exit();
	}
	mysqli_stmt_bind_param($statement, "si", $nuevo_nombre, $alid);
	mysqli_stmt_execute($statement);
	header("Location: ../view_album.php?id=".$alid."&&cur=".$is_current."&&change=success");
	exit();
}

else if(isset($_POST['change_albumyear'])){
	require 'bdh.inc.php';
	$alid = $_POST['to-change'];
	$year = $_POST['year'];
	$is_current = $_POST['is_cur'];
	$sql_query = "UPDATE albumes SET debut_year=?  WHERE id_album=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)){
		header("Location: ../album_edit.php?id=".$alid."&&cur=".$is_current."&&error=sqlerror");
	exit();
	}
	mysqli_stmt_bind_param($statement, "ii", $year, $alid);
	mysqli_stmt_execute($statement);
	header("Location: ../view_album.php?id=".$alid."&&cur=".$is_current."&&change=success");
	exit();
}

else if(isset($_POST['add_toalbum'])) {
	require 'bdh.inc.php';
	$alid = $_POST['aid'];
	$cid = $_POST['cid'];
	$sql_query = "INSERT INTO canciones_albumes VALUES (?, ?)";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)){
		return '';
	}
	mysqli_stmt_bind_param($statement, "ii", $cid, $alid);
	$check = mysqli_stmt_execute($statement);
	if(!$check){
		echo "OKN'T";
		return '';
	}
	echo "OK";
	return '';
}

else if(isset($_POST['delete_fromalbum'])) {
	require 'bdh.inc.php';
	$alid = $_POST['aid'];
	$cid = $_POST['cid'];
	$sql_query = "DELETE FROM canciones_albumes WHERE id_cancion=? AND id_album=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)) {
		echo "xd";
		return '';
	}
	mysqli_stmt_bind_param($statement, "ii", $cid, $alid);
	mysqli_stmt_execute($statement);
	echo "OK";
	return '';
}

else{
	header("Location: ../index.php");
	exit();
}