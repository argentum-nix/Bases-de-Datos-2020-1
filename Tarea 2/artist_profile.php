<?php 
include("includes/header.php");
if(isset($_GET['id']) and isset($_GET['cur'])) {
	$artista_id = $_GET['id'];
	$is_current_user = $_GET['cur'];
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
	<?php
	// solo si no es el perfil de usuario activo se muestra el boton de seguir
	// pero si ya sigo al usuario, debo mostrar "dejar de seguir"
	if(!$is_current_user){
		$curid = $_SESSION['id'];
		$query_follow = mysqli_query($connection, "SELECT * FROM artist_follows WHERE id_persona='$curid' AND id_artista='$artista_id'");
		$fila = mysqli_fetch_row($query_follow);
		// Usuario actual (sea este artista o usuario normal) ya sigue a artista de este perfil
		if($fila){
			echo
			"<form action='includes/follow.inc.php' method='post'>
				<input type='hidden' name='current' value='".$_SESSION['id']."'>
				<input type='hidden' name='is_cur' value='".$is_current_user."'>
				<input type='hidden' name='to-unfollow' value='".$artista_id."'>
				<button class='x-button' name='unfollow_artist' type='submit'>Dejar de seguir</button>
			</form>";
		}
		else{
			echo
			"<form action='includes/follow.inc.php' method='post'>
				<input type='hidden' name='current' value='".$_SESSION['id']."'>
				<input type='hidden' name='is_cur' value='".$is_current_user."'>
				<input type='hidden' name='to-follow' value='".$artista_id."'>
				<button class='x-button' name='follow_artist' type='submit'>Seguir</button>
			</form>";
		}
	}
	$query_canciones = mysqli_query($connection,"SELECT nombre, duracion FROM canciones WHERE id_artista='$artista_id' LIMIT 10");
	$fila = mysqli_fetch_row($query_canciones);
	?>
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
			echo "<p style='color:#b3b3b3'>Artista no tiene cancniones publicadas.</p>";
		}

?>	
	</ul>

</div>

<?php
echo 
	"<a style='text-decoration: none; color:#b3b3b3;text-align:right;'
		href='artist_profile_songs.php?id=".$artista_id."&&name=".$artist."&&count=".$seguidores."&&cur=".$is_current_user."'>
		<p style='padding-right:50px;'>VER MÁS</p>
	</a>"
?>

<h2 class='title mb-3' style="margin-top: 0px">Albumes</h2>
<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT id_album, nombre, debut_year FROM albumes WHERE id_artista ='$artista_id' LIMIT 12");
		$flag = true;
		while($fila1 = mysqli_fetch_array($res)){
			$flag = false;
			echo
			"<div class='col-12 col-md-3 col-lg-2'>
				<div class='card'>
					<a href='view_album.php?id=".$fila1["id_album"]."&&cur=".$is_current_user."'>
						<img class='card-img-top pb-2' src='img/album1.png'>
					</a>
					<div class='card-body p-0'>
						<a href='view_album.php?id=".$fila1["id_album"]."&&cur=".$is_current_user."'>
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
		href='artist_profile_albums.php?id=".$artista_id."&&name=".$artist."&&count=".$seguidores."&&cur=".$is_current_user."'>
		<p style='padding-right:50px;'>VER MÁS</p>
	</a>"
?>
<?php include("includes/footer.php")?>