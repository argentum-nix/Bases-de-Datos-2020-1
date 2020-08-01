<?php 
include("includes/header.php");
?>
<h1 class='title mb-3' style="padding-top:30px; display: flex; font-size: 50px;">Cambiar la contraseña</h1>
<form action="includes/pwd.inc.php" method="post" style="margin-top: 30px; margin-left: 20px;">
				<h5 class='title mb-3' style="padding-top:10px; display: flex; font-size: 16px;">Nueva contraseña</h5>
				<div class="form-input", style="width:50%;">
			  		<input type="text" name="pwd1" placeholder="Nueva contraseña">
				</div>
				<h5 class='title mb-3' style="padding-top:10px; display: flex; font-size: 16px;">Repetir nueva contraseña</h5>
				<div class="form-input" style="width:50%;">
			  		<input type="text" name="pwd2" placeholder="Repite la nueva contraseña">
				</div>
				<div class="button1" style="width:30%;">
					<button type="submit" name="change-pwd">Establecer nueva contraseña</button>
				</div>
			</form>
			<?php 
			if (isset($_GET["error"])) {
				if ($_GET['error'] == 'emptyfields') {
					echo '<div class="error" style="width:50%; margin: 30px auto;">Por favor, rellene todos los campos.</div>';
				}
				else if ($_GET["error"] == 'wrongpwd') {
					echo '<div class="error" style="width:50%; margin: 30px auto;">Contraseñas distintas o no difieren de la actual.</div>';
				}
				else if ($_GET["error"] == 'notequal') {
					echo '<div class="error" style="width:50%; margin: 30px auto;">Contraseñas ingresadas no son iguales.</div>';
				}
			}
			?>

<?php include("includes/footer.php")?>