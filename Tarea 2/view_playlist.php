<?php 
include("includes/header.php");
if(isset($_GET['id']) and isset($_GET['cur'])) {
	$playlist_id = $_GET['id'];
	$is_current = $_GET['cur'];
}
else {
	header("Location: index.php");
	exit();
}

// Se trabaja sobre la vista que contiene canciones asociadas a cada playlist, con su nombre, artista y duracion.
// Ademas, la vista contiene nombre de playlist.
$duracion_playlist = mysqli_query($connection, "SELECT SUM(duracion) FROM vista_playlist WHERE id_playlist='$playlist_id'");
$fila = mysqli_fetch_row($duracion_playlist);
$duracion_playlist = $fila[0];

$query_seguidores = mysqli_query($connection, "SELECT COUNT(id_persona) FROM follow_playlists WHERE id_playlist='$playlist_id'");
$fila = mysqli_fetch_row($query_seguidores);
$seguidores = $fila[0];

$s = $duracion_playlist % 60;
$min = ($duracion_playlist - $s)/60;
$query = mysqli_query($connection, "SELECT * FROM vista_playlist WHERE id_playlist='$playlist_id'");
$total_canciones = mysqli_num_rows($query);
$fila = mysqli_fetch_row($query);

if($fila) {
	$playlist_name = $fila[5];
	$uid = $fila[2];
	$query_aux = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona='$uid'");
	$fila2 = mysqli_fetch_row($query_aux);
	$author = $fila2[0];

}
// Playlist en cuestion esta vacio. Se obtienen manualmente sus datos, pues no esta en la vista.
else {
	$query_aux = mysqli_query($connection, "SELECT nombre, id_usuario FROM playlists WHERE id_playlist='$playlist_id'");
	$fila2 = mysqli_fetch_row($query_aux);
	$playlist_name = $fila2[0];
	$uid =  $fila2[1];
	$query_aux = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona='$uid'");
	$fila2 = mysqli_fetch_row($query_aux);
	$author = $fila2[0];
}
?>


<div class="entityinfo">
	<div class="leftsection">
		<img src="img/playlist.png" style="width: 100%"/>
	</div>
	<div class="rightsection">
		<h2 class='title mb-3' style="margin-top: 0px"><?php echo $playlist_name ?></h2>
		<?php echo "<a style='text-decoration:none;' href='user_profile.php?id=".$uid."&&cur=".$is_current."'><p style='color:#b3b3b3; font-weight: 500; margin-bottom: 0px;text-decoration:none;'>Por ".$author."</p></a>";
		?>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 3px;"><?php echo $total_canciones ?> canciones</p>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 90px;"><?php echo $min ?> min <?php echo $s?> s</p>
		<p style="color:#b3b3b3; font-weight: 400;"><?php echo $seguidores?> seguidores</p>
	

	<?php 

	if(!$is_current){
		$curid = $_SESSION['id'];
		$query_follow = mysqli_query($connection, "SELECT * FROM follow_playlists WHERE id_persona='$curid' AND id_playlist='$playlist_id'");
		$fila1 = mysqli_fetch_row($query_follow);
		// Usuario actual (sea este artista o usuario normal) ya sigue a artista de este perfil
		if($fila1){
			echo
			"<form action='includes/follow.inc.php' method='post'>
				<input type='hidden' name='current_user' value='".$_SESSION['id']."'>
				<input type='hidden' name='is_cur' value='".$is_current."'>
				<input type='hidden' name='to-unfollow' value='".$playlist_id."'>
				<button class='x-button' name='unfollow_playlist' type='submit'>Dejar de seguir</button>
			</form>";
		}
		else{
			echo
			"<form action='includes/follow.inc.php' method='post'>
				<input type='hidden' name='current_user' value='".$_SESSION['id']."'>
				<input type='hidden' name='is_cur' value='".$is_current."'>
				<input type='hidden' name='to-follow' value='".$playlist_id."'>
				<button class='x-button' name='follow_playlist' type='submit'>Seguir</button>
			</form>";
		}
	}
	// actual es el autor, permito el crud
	else {
		echo
		"<a href='playlist_edit.php?id=".$playlist_id."&&cur=".$is_current."'style='float:right; text-decoration:none;'>
			<button class='x-button' type='submit'>Editar el playlist</button>
		</a>";
	}
	?>
	</div>
</div>

<div class="tracklist-container">
	<ul class="tracklist" style='padding:0'>
<?php
	while($fila) {
		$nombre_cancion = $fila[0];
		$duracion = $fila[1];
		$s = $duracion % 60;
		$min = ($duracion - $s)/60;
		$playlist_name = $fila[5];
		$artist = $fila[3];
		$cancion_id = $fila[6];
		$fila = mysqli_fetch_row($query);

		echo 
		"<li class='track'>
			<img class='note' src = 'img/note.png'/>
			<img class='playbutton' src='img/play.png'/>
			<div class='trackinfo'>
				<span class='trackname' style='font-size:17px;'>".$nombre_cancion."</span>
				<span class='trackname' style='color:#b3b3b3; font-weight:400;'>".$artist."</span>

			</div>
			<div class='track-options'>
				<input type='hidden' id='id_cancion' class='cid' value='".$cancion_id."'>
				<button onclick='showOptionsMenu(this)'><img class='optbutton' src='img/dots.png'></button>
			</div>
			<div class='track-duration'>
				<span class='trackdur'>".$min.":".$s."</span>
			</div>
		</li>";
	}

?>
	
	</ul>
</div>

<nav class='optMenu'>
	<input type="hidden" class="cid">
	<?php
	// soy usuario y autor de playlist
		if($is_current){
			// soy usuario, solo puedo dar likes y agregar a playlists
			echo "<div class='item' id='like'></div>";
			// entonces debo poder sacar la cancion de playlist
			echo "<div class='item' onclick='deleteFromPlaylist(this, ".$playlist_id.")'> Borrar de playlist </div>";
			// entonces debo poder agregarla a otro playlist
			echo
				"<select class='item' onchange='addToPlaylist(this)'>
					<option value='' style=''>Agregar a otro playlist</option>";
				$query = mysqli_query($connection, "SELECT * FROM playlists WHERE id_usuario=".$_SESSION['id']);
				$fila = mysqli_fetch_row($query);
				while($fila){
					$pid = $fila[0];
					$nombre = $fila[2];
					echo "<option value='".$pid."'>".$nombre."</option>";
					$fila = mysqli_fetch_row($query);
				}
				echo "</select>";
		}
		else if($_SESSION['usertype'] == 'user' and !$is_current){
			// soy usuario, solo puedo dar likes y agregar a playlists
			echo "<div class='item' id='like'></div>";
			// entonces debo poder agregarla a otro playlist
			echo
				"<select class='item' onchange='addToPlaylist(this)'>
					<option value='' style=''>Agregar a otro playlist</option>";
				$query = mysqli_query($connection, "SELECT * FROM playlists WHERE id_usuario=".$_SESSION['id']);
				$fila = mysqli_fetch_row($query);
				while($fila){
					$pid = $fila[0];
					$nombre = $fila[2];
					echo "<option value='".$pid."'>".$nombre."</option>";
					$fila = mysqli_fetch_row($query);
				}
				echo "</select>";
		}
	?>

</nav>

<?php include("includes/footer.php")?>