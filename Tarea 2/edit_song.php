<?php 
include("includes/header.php");
if(isset($_GET['cid']) and isset($_GET['cur'])) {
	$cid = $_GET['cid'];
	$is_current = $_GET['cur'];
}
else {
	header("Location: index.php");
	exit();
}

	$query = mysqli_query($connection, "SELECT * FROM canciones WHERE id_cancion=".$cid);
	$fila = mysqli_fetch_row($query);
	$aid = $fila[1];
	$nombre = $fila[2];
	$duracion = $fila[3];
	$s = $duracion % 60;
	$min = ($duracion - $s)/60;
	$query = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona=".$aid);
	$fila = mysqli_fetch_row($query);
	$artist = $fila[0];
?>
<div class="entityinfo">
	<div class="leftsection">
		<img src="img/song.jpg" style="width: 100%"/>
	</div>
	<div class="rightsection">
		<h2 class='title mb-3' style="margin-top: 0px">
			<?php
			if($is_current){
				echo
				"<form action='includes/song.inc.php' method='post' style='width:600px;'>
					<div class='form-input' style='display:flex; margin:0;'>
					<input type='text' name='name' placeholder='Nuevo nombre' style='display:flex; margin-right:50px; background: #282828; height: 40px; font-weight: 600; font-size: 20px; padding-left: 20px; color: #fff;'>
					<button class='x-button'type='submit' name='change_songname'>Guardar</button>
					</div>
					<input type='hidden' name='to-change' value='".$cid."'>
					<input type='hidden' name='is_cur' value='".$is_current."'>";
					echo
				"<form action='includes/song.inc.php' method='post' style='width:600px;'>
					<div class='form-input' style='display:flex; margin:0;'>
					<input type='text' name='time' placeholder='Duración en segundos' style='display:flex; margin-right:50px; background: #282828; height: 40px; font-weight: 600; font-size: 20px; padding-left: 20px; color: #fff; width:250px;'>
					<button class='x-button'type='submit' name='change_songlength' style='margin-left:185px;'>Guardar</button>
					</div>
					<input type='hidden' name='to-change' value='".$cid."'>
					<input type='hidden' name='is_cur' value='".$is_current."'>";
			}
			?>
		</h2>
		<?php echo "<a style='text-decoration:none;' href='artist_profile.php?id=".$cid."&&cur=".$is_current."'><p style='color:#b3b3b3; font-weight: 500; margin-bottom: 0px;text-decoration:none;'>Por ".$artist."</p></a>";
		?>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 90px;"><?php echo $min ?> min <?php echo $s?> s</p>
		<?php 
		echo 
		"<form action='includes/song.inc.php' method='post' style='float:left;'>
			<input type='hidden' name='to-delete' value='".$cid."'>
			<button class='x-button' style='float:left;' name='delete_song' type='submit'>Borrar canción</button>
		</form>

		<a href='view_album.php?id=".$cid."&&cur=".$is_current."'style='float:right; text-decoration:none; margin-top:3px;'>
			<button class='x-button'type='submit'>Dejar de editar</button>
		</form>";
	?>
	</div>
</div>



<?php include("includes/footer.php")?>