<?php 
include("includes/header.php");
?>
<h2 class='title mb-3' style="margin-top: 0px">Todos los usuarios que sigo</h2>
<div class="row list mb-5">
	<?php 
	$query = mysqli_query($connection, "SELECT id_usuario FROM user_follows WHERE id_persona =".$_SESSION['id']);
	$flag = true;
	$fila = mysqli_fetch_row($query);
	while($fila){
		$uid = $fila[0];
		if($uid == $_SESSION['id']) {
			$is_current = true;
		}
		else {
			$is_current = false;
		}
		$query_nombre = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona=".$uid);
		$fila2 = mysqli_fetch_row($query_nombre);
		$nombre = $fila2[0];
		$fila = mysqli_fetch_row($query);
		$flag = false;
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
	if($flag){
		echo "<p style='color:#b3b3b3'>Todavía no seguiste a algun usuario.</p>";
	}
	?>
	</div>;

<?php include("includes/footer.php")?>