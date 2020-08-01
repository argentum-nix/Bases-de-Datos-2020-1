<?php 
include("includes/header.php");
if(isset($_GET['id']) and isset($_GET['name']) and isset($_GET['count']) and isset($_GET['cur'])) {
	$artista_id = $_GET['id'];
	$artist = $_GET['name'];
	$seguidores = $_GET['count'];
	$is_current_user = $_GET['cur'];
}
else {
	header("Location: index.php");
	exit();
}

$query_canciones = mysqli_query($connection,"SELECT nombre, duracion FROM canciones WHERE id_artista='$artista_id'");
$fila = mysqli_fetch_row($query_canciones);
?>

<div class="entityinfo">
	<div class="leftsection">
		<img src="img/profile.jpg" style="width: 100%"/>
	</div>
	<div class="rightsection">
		<p style="color:#b3b3b3; font-weight: 500; margin-bottom: 0px;">Perfil de artista</p>
		<h2 class='title mb-3' style="margin-top: 0px"><?php echo $artist ?></h2>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 90px;"><?php echo $seguidores?> seguidores</p>
		
	</div>
</div>

<div class="tracklist-container">
	<ul class="tracklist" style='padding:0'>
	<h2 class='title mb-3' style="margin-top: 0px">Todas las canciones</h2>
<?php
	$flag = true;
	while($fila) {
		$flag = false;
		$nombre_cancion = $fila[0];
		$duracion = $fila[1];
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
				<img class='optbutton' src='img/dots.png'/>
			</div>

			<div class='track-duration'>
				<span class='trackdur'>".$min.":".$s."</span>
			</div>
		</li>";
	}
	if($flag) {
			echo "<p style='color:#b3b3b3'>Artista no tiene cancniones publicadas.</p>";
		}

?>	
	</ul>

</div>
<?php include("includes/footer.php")?>