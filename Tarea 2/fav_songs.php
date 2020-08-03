<?php 
include("includes/header.php");

$query_canciones = mysqli_query($connection,"SELECT * FROM likes_view WHERE id_usuario=".$_SESSION['id']);
$fila = mysqli_fetch_row($query_canciones);
?>

<div class="entityinfo">
	<div class="leftsection">
		<img src="img/heart.jpg" style="width: 100%"/>
	</div>
	<div class="rightsection">
		<h2 class='title mb-3' style="margin-top: 0px">Tus canciones favoritas</h2>
		
	</div>
</div>

<div class="tracklist-container">
	<ul class="tracklist" style='padding:0'>
	<h2 class='title mb-3' style="margin-top: 0px">Lo que te gusta</h2>
<?php
	$flag = true;
	while($fila) {
		$flag = false;
		$nombre_cancion = $fila[1];
		$duracion = $fila[3];
		$artist = $fila[2];
		$cancion_id = $fila[4];
		$s = $duracion % 60;
		$min = ($duracion - $s)/60;
		$fila = mysqli_fetch_row($query_canciones);

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
	if($flag) {
			echo "<p style='color:#b3b3b3'>Todavía no tienes canciones favoritas.</p>";
		}

?>	
	</ul>

</div>
<nav class='optMenu'>
	<input type="hidden" class="cid">
	<?php
	// soy usuario actual
	// solo puedo dar likes y agregar a playlists
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
	?>

</nav>


<?php include("includes/footer.php")?>