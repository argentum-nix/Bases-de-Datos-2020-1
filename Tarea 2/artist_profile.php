<?php 
include("includes/header.php");
if(isset($_GET['id'])) {
	$artista_id = $_GET['id'];
}
else {
	header("Location: index.php");
	exit();
}

$query_nombre = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona='$artista_id'");
$fila = mysqli_fetch_row($query_nombre);
$artist = $fila[0];

$query_seguidores = mysqli_query($connection, "SELECT COUNT(id_persona1) FROM follows WHERE id_persona2='$artista_id'");
$fila = mysqli_fetch_row($query_seguidores);
$seguidores = $fila[0];

$query_canciones = mysqli_query($connection,"SELECT nombre, duracion FROM canciones WHERE id_artista='$artista_id' LIMIT 10");
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
	<h2 class='title mb-3' style="margin-top: 0px">Canciones</h2>
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
			echo "Artista no tiene cancniones publicadas.";
		}

?>	
	</ul>

</div>

<?php
echo 
	"<a style='text-decoration: none; color:#b3b3b3;text-align:right;'
		href='artist_profile_songs.php?id=".$artista_id."&&name=".$artist."&&count=".$seguidores."'>
		<p style='padding-right:50px;'>VER MÁS</p>
	</a>"
?>

<h2 class='title mb-3' style="margin-top: 0px">Albumes</h2>
<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT id_album, nombre, debut_year FROM albumes WHERE id_artista ='$artista_id' LIMIT 10");
		$flag = true;
		while($fila1 = mysqli_fetch_array($res)){
			$flag = false;
			echo
			"<div class='col-12 col-md-3 col-lg-2'>
				<div class='card'>
					<a href='view_album.php?id=".$fila1["id_album"]."'>
						<img class='card-img-top pb-2' src='img/album1.png'>
					</a>
					<div class='card-body p-0'>
						<a href='view_album.php?id=".$fila1["id_album"]."'>
							<h5 class='card-title'>".$fila1["nombre"]."</h5>
						</a>
						<p class='card-text'>".$fila1["debut_year"]."</p>
					</div>
				</div>
			</div>";
		}
		if($flag) {
			echo "Artista no tiene albumes publicados.";
		}
		?>
	</div>

<?php
echo 
	"<a style='text-decoration: none; color:#b3b3b3;text-align:right;'
		href='artist_profile_albums.php?id=".$artista_id."&&name=".$artist."&&count=".$seguidores."'>
		<p style='padding-right:50px;'>VER MÁS</p>
	</a>"
?>
<?php include("includes/footer.php")?>