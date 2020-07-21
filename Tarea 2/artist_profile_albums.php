<?php 
include("includes/header.php");
if(isset($_GET['id'])) {
	$artista_id = $_GET['id'];
	$artist = $_GET['name'];
	$seguidores = $_GET['count'];
}
else {
	header("Location: index.php");
	exit();
}
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

<h2 class='title mb-3' style="margin-top: 0px">Todos los albumes</h2>
<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT id_album, nombre, debut_year FROM albumes WHERE id_artista ='$artista_id'");
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