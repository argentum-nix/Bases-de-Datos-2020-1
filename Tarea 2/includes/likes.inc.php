<?php
session_start();
if(isset($_POST['check'])){
	require 'bdh.inc.php';
	$cid = $_POST['cid'];
	$uid = $_SESSION['id'];

	$sql_query = "SELECT * FROM likes_canciones WHERE id_usuario=? AND id_cancion=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)) {
		echo "xd";
		return '';
	}
	mysqli_stmt_bind_param($statement, "ii", $uid, $cid);
	mysqli_stmt_execute($statement);
	mysqli_stmt_store_result($statement);

	$check = mysqli_stmt_num_rows($statement);
	if($check >> 0) {
		echo "true";
		return '';
	}
	else {
		echo "false";
		return '';
	}
}
else if(isset($_POST['dislike'])){
	require 'bdh.inc.php';
	$cid = $_POST['cid'];
	$uid = $_SESSION['id'];
	$sql_query = "DELETE FROM likes_canciones WHERE id_usuario=? AND id_cancion=?";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)) {
		echo "xd";
		return '';
	}
	mysqli_stmt_bind_param($statement, "ii", $uid, $cid);
	mysqli_stmt_execute($statement);
}
else if(isset($_POST['like'])){
	require 'bdh.inc.php';
	$cid = $_POST['cid'];
	$uid = $_SESSION['id'];
	$sql_query = "INSERT INTO likes_canciones VALUES (?,?)";
	$statement = mysqli_stmt_init($connection);
	if (!mysqli_stmt_prepare($statement, $sql_query)) {
		echo "xd";
		return '';
	}
	mysqli_stmt_bind_param($statement, "ii", $cid, $uid);
	mysqli_stmt_execute($statement);
}


?>