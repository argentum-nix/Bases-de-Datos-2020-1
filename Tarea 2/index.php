<?php 
include("includes/header.php");
?>
<?php if($_SESSION['usertype'] == 'user'):?>
	<h2 class='title mb-3'>Hecho para <?php echo $_SESSION['nombre'];?>:</h2>
	<p style="color:#b3b3b3">Cuanto más escuches, mejores serán las recomendaciones.</p>
	<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT nombre, id_artista FROM albumes LIMIT 20");
		while($fila1 = mysqli_fetch_array($res)){
			$album_name = $fila1["nombre"];
			$aid = $fila1["id_artista"];
			$query_artista = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona=".$aid);
			$fila2 = mysqli_fetch_array($query_artista);
			$artist_name = $fila2["nombre"];
		?>
		<div class="col-12 col-md-3 col-lg-2">
			<div class="card">
				<a href="">
					<img class="card-img-top pb-2" src="img/album1.png">
				</a>
				<div class="card-body p-0">
					<a href="">
						<h5 class="card-title"><?php echo $album_name?></h5>
					</a>
					<a href="">
						<p class="card-text"><?php echo $artist_name?></p>
					</a>
				</div>
			</div>
		</div>
		<?php }; ?>
	</div>
<?php else:?>

	<h2 class='title mb-3'>Algunas de tus creaciones, <?php echo $_SESSION['nombre'];?>:</h2>
	<div class="row list mb-5">
		<?php
		$aid = $_SESSION["id"];
		$res = mysqli_query($connection, "SELECT nombre, debut_year FROM albumes WHERE id_artista ='$aid' LIMIT 20");
		while($fila1 = mysqli_fetch_array($res)){
			$album_name = $fila1["nombre"];
			$year = $fila1["debut_year"];
		?>
		<div class="col-12 col-md-3 col-lg-2">
			<div class="card">
				<a href="">
					<img class="card-img-top pb-2" src="img/album1.png">
				</a>
				<div class="card-body p-0">
					<a href="">
						<h5 class="card-title"><?php echo $album_name?></h5>
					</a>
					<a href="">
						<p class="card-text"><?php echo $year?></p>
					</a>
				</div>
			</div>
		</div>
		<?php }; ?>
	</div>

<?php endif;?>
<?php include("includes/footer.php")?>