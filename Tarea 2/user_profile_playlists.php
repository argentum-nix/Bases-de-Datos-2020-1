<?php 
include("includes/header.php");
if(isset($_GET['id']) and isset($_GET['name']) and isset($_GET['cur'])) {
	$user_id = $_GET['id'];
	$username = $_GET['name'];
	$is_current_user = $_GET['cur'];
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
		<p style="color:#b3b3b3; font-weight: 500; margin-bottom: 0px;">Perfil de usuario</p>
		<h2 class='title mb-3' style="margin-top: 0px"><?php echo $username ?></h2>
	</div>
</div>

<h2 class='title mb-3' style="margin-top: 0px">Todas las playlists</h2>
<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT id_playlist, nombre FROM playlists WHERE id_usuario ='$user_id'");
		$flag = true;
		while($fila1 = mysqli_fetch_array($res)){
			$flag = false;
			echo
			"<div class='col-12 col-md-3 col-lg-2'>
				<div class='card'>
					<a href='view_playlist.php?id=".$fila1["id_playlist"]."&&cur=".$is_current_user."'>
						<img class='card-img-top pb-2' src='img/playlist.png'>
					</a>
					<div class='card-body p-0'>
						<a href='view_playlist.php?id=".$fila1["id_playlist"]."&&cur=".$is_current_user."'>
							<h5 class='card-title'>".$fila1["nombre"]."</h5>
						</a>
					</div>
				</div>
			</div>";
		}
		if($flag) {
			if($is_current_user){
				echo "<p style='color:#b3b3b3'>Todavía no hiciste una playlist.</p>";
			}
			else{
				echo "<p style='color:#b3b3b3'>Usuario todavía no creó una playlist.</p>";
			}
		}
		?>
	</div>

<?php include("includes/footer.php")?>