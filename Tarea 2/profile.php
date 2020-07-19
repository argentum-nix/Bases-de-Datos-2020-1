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
	aqui
	van
	las
	cancniones
<?php include("includes/footer.php")?>