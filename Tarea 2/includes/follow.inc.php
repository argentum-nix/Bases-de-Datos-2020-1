<?php 
session_start();
if(isset($_POST['follow_artist'])) {
	require 'bdh.inc.php';
	$current = $_POST['current'];
	$to_follow = $_POST['to-follow'];
	$is_current = $_POST['is_cur'];
	$query =  mysqli_query($connection, "INSERT INTO follows (id_persona1, id_persona2) VALUES ('$current', '$to_follow')");
	header("Location: ../artist_profile.php?id=".$to_follow."&&cur=".$is_current."&&follow=success");
	mysqli_close($connection);
	exit();

}
else if(isset($_POST['unfollow_artist'])){
	require 'bdh.inc.php';
	$current = $_POST['current'];
	$to_unfollow = $_POST['to-unfollow'];
	$is_current = $_POST['is_cur'];
	$query =  mysqli_query($connection, "DELETE FROM follows  WHERE id_persona2='$to_unfollow'");
	header("Location: ../artist_profile.php?id=".$to_unfollow."&&cur=".$is_current."&&unfollow=success");
	mysqli_close($connection);
	exit();
	
}

else if(isset($_POST['follow_user'])){
	require 'bdh.inc.php';
	$current = $_POST['current'];
	$to_follow = $_POST['to-follow'];
	$is_current = $_POST['is_cur'];
	$query =  mysqli_query($connection, "INSERT INTO follows (id_persona1, id_persona2) VALUES ('$current', '$to_follow')");
	header("Location: ../user_profile.php?id=".$to_follow."&&cur=".$is_current."&&follow=success");
	mysqli_close($connection);
	exit();
}

else if(isset($_POST['unfollow_user'])){
	require 'bdh.inc.php';
	$current = $_POST['current'];
	$to_unfollow = $_POST['to-unfollow'];
	$is_current = $_POST['is_cur'];
	$query =  mysqli_query($connection, "DELETE FROM follows  WHERE id_persona2='$to_unfollow'");
	header("Location: ../user_profile.php?id=".$to_unfollow."&&cur=".$is_current."&&unfollow=success");
	mysqli_close($connection);
	exit();
}

else if(isset($_POST['follow_playlist'])){
	require 'bdh.inc.php';
	$current_user = $_POST['current_user'];
	$to_follow = $_POST['to-follow'];
	$query =  mysqli_query($connection, "INSERT INTO follow_playlists (id_persona, id_playlist) VALUES ('$current_user', '$to_follow')");
	header("Location: ../view_playlist.php?id=".$to_follow."&&cur=".$is_current."&&follow=success");
	mysqli_close($connection);
	exit();
	

}

else if(isset($_POST['unfollow_playlist'])){
	require 'bdh.inc.php';
	$current_user = $_POST['current_user'];
	$to_unfollow = $_POST['to-unfollow'];
	$query =  mysqli_query($connection, "DELETE FROM follow_playlists WHERE id_playlist='$to_unfollow' AND id_persona='$current_user'");
	header("Location: ../view_playlist.php?id=".$to_unfollow."&&cur=".$is_current."&&unfollow=success");
	mysqli_close($connection);
	exit();

}


else{
	header("Location: ../index.php");
	exit();
}