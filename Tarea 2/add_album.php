<?php 
include("includes/header.php");
?>
<form action="includes/album.inc.php" method="post">
	<button class="x-button" name="cancel_creation" type="submit">X</button>
	<h2 class='title mb-3'>Agrega nuevo album</h2>
	<div class="form-input" style="display:flex; margin:0; padding-bottom: 20px;">
		<input type="text" name="albumname" placeholder="Nuevo album..." style="display:flex; margin:0; background: #282828; height: 70px; font-weight: 600; font-size: 25px; padding-left: 20px; color: #fff">
	</div>
	<div class="form-input" style="display:flex; margin:0; padding-bottom: 20px;">
		<input type="text" name="year" placeholder="Año de estreno..." style="display:flex; margin:0; background: #282828; height: 50px; font-weight: 600; font-size: 25px; padding-left: 20px; color: #fff">
	</div>

	<div class="button1 pl-2" style="display:flex; margin:0; width: 100px; opacity: .8;">
		<button type="submit" name="create_album">Crear</button>
	</div>
</form>

<?php
if (isset($_GET["error"])) {
	if ($_GET['error'] == 'emptyfields') {
		echo '<div class="error" style="margin-top:30px;display:flex;width:50%;opacity:.7;">Por favor, rellene todos los campos.</div>';
	}
}
if (isset($_GET["operation"])) {
	if ($_GET["operation"] == "success"){
		echo '<div class="success" style="margin-top:30px;display:flex;width:50%;opacity:.7;">¡Nuevo album ha sido agregado a nuestra base de datos!</div>';
	}
}
?>
<?php include("includes/footer.php")?>