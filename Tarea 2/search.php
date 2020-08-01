<?php 
include("includes/header.php");

if (isset($_GET['item'])){
	$item = urldecode($_GET['item']);
}
else{
	$item = "";
}
?>

<form action="includes/search.inc.php" method="post">
	<div class="form-input">
			<input type="text" name="search-input" value="<?php echo $item?>" placeholder="Busca artista, usuario, cancion, album o playlist...">
	</div>
</form>


<?php

// Busqueda por albumes
if($item){
	$flaga = false;
	$query_albumes = mysqli_query($connection,"SELECT P.nombre as nombre_artista, A.id_artista, A.nombre as nombre_album, A.id_album FROM albumes A, personas P WHERE P.id_persona=A.id_artista AND A.nombre LIKE '%$item%'");

	$fila = mysqli_fetch_row($query_albumes);
	if($fila){
		echo 
		"<h2 class='title mb-3' style='margin-top: 0px'>Albumes</h2>
		<div class='row list mb-5'>";
		$flaga = true;
	}
	while($fila) {
		$alid = $fila[3];
		$artist = $fila[0];
		$album = $fila[2];
		$aid = $fila[1];
		if($aid == $_SESSION['id']) {
			$is_current = true;
		}
		else {
			$is_current = false;
		}
		$fila = mysqli_fetch_row($query_albumes);
		echo
		"<div class='col-12 col-md-3 col-lg-2'>
			<div class='card'>
				<a href='view_album.php?id=".$alid."&&cur=".$is_current."'>
					<img class='card-img-top pb-2' src='img/album1.png'>
				</a>
				<div class='card-body p-0'>
					<a href='view_album.php?id=".$alid."&&cur=".$is_current."'>
						<h5 class='card-title'>".$album."</h5>
					</a>
					<a style='text=decoration:none;' href='artist_profile.php?id=".$aid."&&cur=".$is_current."'><p class='card-text'>".$artist."</p></a>
				</div>
			</div>
		</div>";

		}
	if($flaga){
		echo "</div>";
	}

}

// Busqueda por playlists
if($item){
	$flagp = false;
	$query_playlists = mysqli_query($connection,"SELECT P.nombre as nombre_usuario, PL.id_usuario, PL.id_playlist, PL.nombre as nombre_playlist FROM playlists PL, personas P WHERE P.id_persona=PL.id_usuario AND PL.nombre LIKE '%$item'");
	$fila = mysqli_fetch_row($query_playlists);
	if($fila){
		echo 
		"<h2 class='title mb-3' style='margin-top: 0px'>Playlists</h2>
		<div class='row list mb-5'>";
		$flagp = true;
	}

	while($fila){
		$nombre_playlist = $fila[3];
		$pid = $fila[2];
		$uid = $fila[1];
		$nombre_usuario = $fila[0];

		if($uid == $_SESSION['id']) {
			$is_current = true;
		}
		else {
			$is_current = false;
		}
		$fila = mysqli_fetch_row($query_playlists);
			echo
			"<div class='col-12 col-md-3 col-lg-2'>
				<div class='card'>
					<a href='view_playlist.php?id=".$pid."&&cur=".$is_current."'>
						<img class='card-img-top pb-2' src='img/playlist.png'>
					</a>
					<div class='card-body p-0'>
						<a href='view_playlist.php?id=".$pid."&&cur=".$is_current."'>
							<h5 class='card-title'>".$nombre_playlist."</h5>
						</a>
						<a href='user_profile.php?id=".$uid."&&cur=".$is_current."'>
							<p class='card-text'>".$nombre_usuario."</p>
						</a>
					</div>
				</div>
			</div>";
		}
	if($flagp){
		echo "</div>";
	}

}

// Busqueda por artista
if($item){
	$flagar = false;
	$query_artistas = mysqli_query($connection,"SELECT P.nombre, A.id_artista FROM personas P, artistas A WHERE P.id_persona=A.id_artista AND P.nombre LIKE '%$item%'");
	$fila = mysqli_fetch_row($query_artistas);
	if($fila){
		echo 
		"<h2 class='title mb-3' style='margin-top: 0px'>Artistas</h2>
		<div class='row list mb-5'>";
		$flagar = true;
	}
	while($fila){
		$nombre = $fila[0];
		$aid = $fila[1];
		if($aid == $_SESSION['id']) {
			$is_current = true;
		}
		else {
			$is_current = false;
		}
		$fila = mysqli_fetch_row($query_artistas);
		echo
			"<div class='col-12 col-md-3 col-lg-2'>
					<div class='card'>
						<a href='artist_profile.php?id=".$aid."&&cur=".$is_current."'>
							<img class='card-img-top pb-2' src='img/artist_profile.png'>
						</a>
						<div class='card-body p-0'>
							<a href='artist_profile.php?id=".$aid."&&cur=".$is_current."'>
								<h5 class='card-title'>".$nombre."</h5>
							</a>
						</div>
					</div>
				</div>";
	}
	if($flagar){
		echo "</div>";
	}

}
// Busqueda por usuarios
if($item){
	$flagi = false;
	$query_usuarios = mysqli_query($connection,"SELECT P.nombre, U.id_usuario FROM personas P, usuarios U WHERE P.id_persona=U.id_usuario AND P.nombre LIKE '%$item%'");
	$fila = mysqli_fetch_row($query_usuarios);
	if($fila){
		echo 
		"<h2 class='title mb-3' style='margin-top: 15px'>Usuarios</h2>
		<div class='row list mb-5'>";
		$flagi = true;
	}
	while($fila){
		$nombre = $fila[0];
		$uid = $fila[1];
		if($uid == $_SESSION['id']) {
			$is_current = true;
		}
		else {
			$is_current = false;
		}
		$fila = mysqli_fetch_row($query_usuarios);
		echo
			"<div class='col-12 col-md-3 col-lg-2'>
					<div class='card'>
						<a href='user_profile.php?id=".$uid."&&cur=".$is_current."'>
							<img class='card-img-top pb-2' src='img/user_profile.png'>
						</a>
						<div class='card-body p-0'>
							<a href='user_profile.php?id=".$uid."&&cur=".$is_current."'>
								<h5 class='card-title'>".$nombre."</h5>
							</a>
						</div>
					</div>
				</div>";
	}
	if($flagi){
		echo "</div>";
	}
}
// Busqueda por canciones
if($item){
	$query_canciones = mysqli_query($connection,"SELECT * FROM vista_cancion WHERE nombre_cancion LIKE '%$item%'");
	$fila = mysqli_fetch_row($query_canciones);
	$flagc = false;
	if ($fila){
		echo 
		"<div class='tracklist-container'>
		<ul class='tracklist' style='padding:0'>
		<h2 class='title mb-3' style='margin-top: 10px'>Canciones</h2>";
		$flagc = true;
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
	if($flagc){
		echo "</ul>";
		echo "</div>";
	}
}
?>

<?php include("includes/footer.php")?>

