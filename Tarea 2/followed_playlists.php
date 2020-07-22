<?php 
include("includes/header.php");?>

<h2 class='title mb-3' style="margin-top: 0px">Todas las playlists que sigo</h2>
<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT id_playlist, nombre, nombre_usuario FROM play_follows WHERE id_persona =".$_SESSION['id']);
		$flag = true;
		while($fila1 = mysqli_fetch_array($res)){
			$flag = false;
			echo
			"<div class='col-12 col-md-3 col-lg-2'>
				<div class='card'>
					<a href='view_playlist.php?id=".$fila1["id_playlist"]."&&cur=".false."'>
						<img class='card-img-top pb-2' src='img/playlist.png'>
					</a>
					<div class='card-body p-0'>
						<a href='view_playlist.php?id=".$fila1["id_playlist"]."&&cur=".false."'>
							<h5 class='card-title'>".$fila1["nombre"]."</h5>
						</a>
						<a href='artist_profile.php?id=".$fila1['id_playlist']."&&cur=".false."'>
							<p class='card-text'>".$fila1['nombre_usuario']."</p>
						</a>
					</div>
				</div>
			</div>";
		}
		if($flag) {
			echo "<p style='color:#b3b3b3'>Todavía no seguiste una playlist.</p>";
		}
		?>
	</div>

<?php include("includes/footer.php")?>