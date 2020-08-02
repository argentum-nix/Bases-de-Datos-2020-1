<?php 
include("includes/header.php");
?>
<h1 class='title mb-3' style="padding-top:30px; display: flex; font-size: 50px;">Eliminar la cuenta</h1>
<h4 class='title mb-3' style="padding-top:10px; display: flex;">¿Desea eliminar su cuenta?</h4>
<p style="color:#fff">Una vez que se elimina su cuenta, no puede reactivarla, recuperar ningún dato (playlists, canciones, etc) o recuperar el acceso.Tendrá que configurar una nueva cuenta si desea usar Poyofy nuevamente. </p>
<form action="includes/mydata.inc.php" method="post">
	<div class="button1 pl-2" style="display:flex; margin:0; width: 100px; opacity: .8;">
		<button type="submit" name="delete-account">Eliminar</button>
	</div>
</form>

<?php include("includes/footer.php")?>