<?php 
include("includes/header.php");
?>
<h1 class='title mb-3' style="padding-top:30px; display: flex; font-size: 50px;">Editar perfil</h1>
<form action="includes/mydata.inc.php" method="post" style="margin-top: 30px; margin-left: 20px;">
	<h5 class='title mb-3' style="padding-top:10px; display: flex; font-size: 16px;">Nombre</h5>
	<div class="form-input", style="width:50%;">
		<input type="text" name="name" placeholder="Nuevo nombre">
	</div>
	<h5 class='title mb-3' style="padding-top:10px; display: flex; font-size: 16px;">Email</h5>
		<div class="form-input" style="width:50%;">
			<input type="text" name="email" placeholder="Nueva dirección de correo electrónico">
		</div>
		<div class="button1" style="width:20%;">
			<button type="submit" name="change-data">Guardar perfil</button>
		</div>
	</form>
		<?php 
			if (isset($_GET["error"])) {
				if ($_GET['error'] == 'mailexists') {
					echo '<div class="error" style="width:50%; margin: 30px auto;">El correo electrónico ya está registrado en el sistema.</div>';
				}
			}
			else if (isset($_GET["operation"])) {
				if ($_GET["operation"] == "success"){
					echo '<div class="success" style="width:50%; margin: 30px auto;">Datos actualizados!.</div>';
				}
			}
			?>
<?php include("includes/footer.php")?>