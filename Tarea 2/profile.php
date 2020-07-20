<?php 
include("includes/header.php");
?>
	<div class="data-container">
		<div class="profile" style="padding-top: 50px; color:white; padding-left: 50px;">
				<img src="img/profile.png" class="rounded-circle" width=180px>
		</div>
		<?php if($_SESSION['usertype'] == 'user'):?>
			<h4 class='title mb-3' style="padding-top:30px; display: flex;">Perfil de usuario</h4>
		<?php else: ?>
			<h4 class='title mb-3' style="padding-top:30px; display: flex;">Perfil de artista</h4>
		<?php endif; ?>
		<h1 class='title' style="font-size:70px;padding-top:80px;display: flex;"><?php echo $_SESSION["nombre"]?></h1>
		<button class="x-button mt-3 mb-3 ml-3" name="cancel_creation" type="submit" style="position: absolute; font-size: 23px;font-weight: 400">Seguir</button>
	</div>


<?php if($_SESSION['usertype'] == 'user'):?>
	<h2 class='title mb-3'>Tus playlists:</h2>
	<p style="color:#b3b3b3">Revisa algunas de tus creaciones:</p>
	<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT * FROM playlists WHERE id_usuario=".$_SESSION["id"]);
		while($fila1 = mysqli_fetch_array($res)){
			$playlist_name = $fila1["nombre"];
			$aid = $fila1["id_artista"];
			$query_artista = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona=".$aid);
			$fila2 = mysqli_fetch_array($query_artista);
			$artist_name = $fila2["nombre"];
		?>
		<div class="col-12 col-md-3 col-lg-2">
			<div class="card">
				<a href="view_album.php">
					<img class="card-img-top pb-2" src="img/album1.png">
				</a>
				<div class="card-body p-0">
					<a href="view_album.php">
						<h5 class="card-title"><?php echo $album_name?></h5>
					</a>
					<a href="profile.php">
						<p class="card-text"><?php echo $artist_name?></p>
					</a>
				</div>
			</div>
		</div>
		<?php }; ?>
	</div>


<?php else:?>
	<h2 class='title mb-3'>Tus albumes:</h2>
	<div class="row list mb-5">
		<?php
		$aid = $_SESSION["id"];
		$res = mysqli_query($connection, "SELECT id_album, nombre, debut_year FROM albumes WHERE id_artista ='$aid' LIMIT 20");
		while($fila1 = mysqli_fetch_array($res)){
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
		?>
	</div>

<?php endif;?>
<?php include("includes/footer.php")?>