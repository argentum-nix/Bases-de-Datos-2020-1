<?php 
include("includes/header.php");
if (isset($_GET['item'])){
	$item = urldecode($_GET['item']);
}

else{
	$item = "";
}

?>
<form action="search.php" method="post">
	<div class="form-input">
			<input type="text" name="search-input" value="<?php echo $item?>" placeholder="Busca artista, usuario, cancion, album o playlist...">
	</div>
</form>


<?php

if($item){
	$query_canciones = mysqli_query($connection,"SELECT * FROM vista_cancion WHERE nombre_cancion LIKE '%$item%'");
	$fila = mysqli_fetch_row($query_canciones);
	if ($fila){
		echo 
		"<div class='tracklist-container'>
		<ul class='tracklist' style='padding:0'>
		<h2 class='title mb-3' style='margin-top: 0px'>Canciones</h2>";
	}
		while($fila) {
			$nombre_cancion = $fila[4];
			$duracion = $fila[2];
			$artist = $fila[3];
			$aid = $fila[1];
			$s = $duracion % 60;
			$min = ($duracion - $s)/60;
			$fila = mysqli_fetch_row($query_canciones);
			if($aid == $_SESSION['id']){
				$is_current = true;
			}
			else{
				$is_current = false;
			}
			echo 
			"<li class='track'>
				<img class='note' src = 'img/note.png'/>
				<img class='playbutton' src='img/play.png'/>
				<div class='trackinfo'>
					<span class='trackname' style='font-size:17px;'>".$nombre_cancion."</span>
					<a style='text-decoration:none;' href='artist_profile.php?id=".$aid."&&cur=".$is_current."'><span class='trackname' style='color:#b3b3b3; font-weight:400;'>".$artist."</span></a>

				</div>
				<div class='track-options'>
					<img class='optbutton' src='img/dots.png'/>
				</div>

				<div class='track-duration'>
					<span class='trackdur'>".$min.":".$s."</span>
				</div>
			</li>";
	}
}
?>
	</ul>
</div>

<?php include("includes/footer.php")?>