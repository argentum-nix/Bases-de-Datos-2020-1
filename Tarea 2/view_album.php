<?php 
include("includes/header.php");
if(isset($_GET['id'])) {
	$album_id = $_GET['id'];
}
else {
	header("Location: index.php");
	exit();
}

$query = mysqli_query($connection,
								"CREATE OR REPLACE VIEW vista_album as
								SELECT C.nombre as nombre_cancion,
								C.duracion,
								P.nombre as nombre_artista,
								A.debut_year,
								A.nombre as nombre_album
								FROM canciones_albumes CA, canciones C, personas P, albumes A
								WHERE CA.id_album = '$album_id'
								AND A.id_album = CA.id_album 
								AND CA.id_cancion = C.id_cancion
								AND C.id_artista = P.id_persona");
$query = mysqli_query($connection, "SELECT * FROM vista_album");
$total_canciones = mysqli_num_rows($query);
$fila = mysqli_fetch_row($query);

if($fila) {
	$year = $fila[3];
	$album_name = $fila[4];
	$artist = $fila[2];
}
else {
	$query_aux = mysqli_query($connection, "SELECT nombre, id_artista FROM albumes WHERE id_album='$album_id'");
	$fila2 = mysqli_fetch_row($query_aux);
	$album_name = $fila2[0];
	$aid =  $fila2[1];
	$query_aux = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona='$aid'");
	$fila2 = mysqli_fetch_row($query_aux);
	$artist = $fila2[0];
}

?>
<div class="entityinfo">
	<div class="leftsection">
		<img src="img/album1.png" style="width: 100%"/>
	</div>
	<div class="rightsection">
		<h2 class='title mb-3' style="margin-top: 0px"><?php echo $album_name ?></h2>
		<p style="color:#b3b3b3; font-weight: 500; margin-bottom: 0px;">Por <?php echo $artist ?></p>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 3px;"><?php echo $total_canciones ?> canciones</p>
	</div>
</div>


<div class="tracklist-container">
	<ul class="tracklist" style='padding:0'>
<?php
	while($fila) {
		$nombre_cancion = $fila[0];
		$duracion = $fila[1];
		$year = $fila[3];
		$album_name = $fila[4];
		$artist = $fila[2];
		$fila = mysqli_fetch_row($query);

		echo 
		"<li class='track'>
			<img class='playbutton' src='img/play.png'/>
			<div class='trackinfo'>
				<span class='trackname'>".$nombre_cancion."</span>
				<span class='trackname'>".$artist."</span>

			</div>
			<div class='track-options'>
				<img class='optbutton' src='img/dots.png'/>
			</div>

			<div class='track-duration'>
				<span class='trackdur'>".$duracion."</span>
			</div>
		</li>";
	}

?>
	
		
	</ul>
</div>



<?php include("includes/footer.php")?>