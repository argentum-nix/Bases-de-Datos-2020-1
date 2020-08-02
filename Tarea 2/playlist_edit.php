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
	$uid = $fila[2];
	$query_aux = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona='$uid'");
	$fila2 = mysqli_fetch_row($query_aux);
	$author = $fila2[0];

}
// Playlist en cuestion esta vacio. Se obtienen manualmente sus datos, pues no esta en la vista.
else {
	$query_aux = mysqli_query($connection, "SELECT nombre, id_usuario FROM playlists WHERE id_playlist='$playlist_id'");
	$fila2 = mysqli_fetch_row($query_aux);
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
		<h2 class='title mb-3' style="margin-top: 0px">
			<?php
			if($is_current){
				echo
				"<form action='includes/playlist.inc.php' method='post' style='width:600px;'>
					<div class='form-input' style='display:flex; margin:0;'>
					<input type='text' name='name' placeholder='Nuevo nombre' style='display:flex; margin-right:50px; background: #282828; height: 40px; font-weight: 600; font-size: 20px; padding-left: 20px; color: #fff;'>
					<button class='x-button'type='submit' name='change_playname'>Guardar</button>
					</div>
					<input type='hidden' name='to-change' value='".$playlist_id."'>
					<input type='hidden' name='is_cur' value='".$is_current."'>";
			}
			?>
		</h2>
		<?php echo "<a style='text-decoration:none;' href='user_profile.php?id=".$uid."&&cur=".$is_current."'><p style='color:#b3b3b3; font-weight: 500; margin-bottom: 0px;text-decoration:none;'>Por ".$author."</p></a>";
		?>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 3px;"><?php echo $total_canciones ?> canciones</p>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 90px;"><?php echo $min ?> min <?php echo $s?> s</p>
		<p style="color:#b3b3b3; font-weight: 400;"><?php echo $seguidores?> seguidores</p>
	

	<?php 
		echo 
		"<form action='includes/playlist.inc.php' method='post' style='float:left;'>
			<input type='hidden' name='to-delete' value='".$playlist_id."'>
			<button class='x-button' style='float:left;' name='delete_playlist' type='submit'>Borrar</button>
		</form>

		<a href='view_playlist.php?id=".$playlist_id."&&cur=".$is_current."'style='float:right; text-decoration:none; margin-top:3px;'>
			<button class='x-button'type='submit'>Dejar de editar</button>
		</form>";
	?>
	</div>
</div>


<?php include("includes/footer.php")?>