<?php 
include("includes/header.php");?>

<h2 class='title mb-3' style="margin-top: 0px">Todas las playlists que sigo</h2>
<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT id_playlist, nombre, nombre_usuario FROM play_follows WHERE id_persona =".$_SESSION['id']);
		$flag = true;
		$fila = mysqli_fetch_row($res);
		while($fila){
			$pid = $fila[0];
			$nombre_playlist = $fila[1];
			$usuario = $fila[2];
			$query_aux = mysqli_query($connection, "SELECT id_usuario FROM playlists WHERE id_playlist='$pid'");
			$fila_aux = mysqli_fetch_row($query_aux);
			$uid = $fila_aux[0];
			$flag = false;
			$fila = mysqli_fetch_row($query_aux);
			echo
			"<div class='col-12 col-md-3 col-lg-2'>
				<div class='card'>
					<a href='view_playlist.php?id=".$pid."&&cur=".false."'>
						<img class='card-img-top pb-2' src='img/playlist.png'>
					</a>
					<div class='card-body p-0'>
						<a href='view_playlist.php?id=".$pid."&&cur=".false."'>
							<h5 class='card-title'>".$nombre_playlist."</h5>
						</a>
						<a href='user_profile.php?id=".$uid."&&cur=".false."'>
							<p class='card-text'>".$usuario."</p>
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