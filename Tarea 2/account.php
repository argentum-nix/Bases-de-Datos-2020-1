<?php 
include("includes/header.php");
?>
<h1 class='title mb-3' style="padding-top:30px; display: flex; font-size: 50px;">Vista general de la cuenta</h1>
<h4 class='title mb-3' style="padding-top:10px; display: flex;">Perfil</h4>
<?php
	$query_datos = mysqli_query($connection, "SELECT * FROM personas WHERE id_persona=".$_SESSION["id"]);
	$fila = mysqli_fetch_row($query_datos);
	$nombre = $fila[1];
	$mail = $fila[2];
?>
<article style="margin-bottom: 2px;">
	<section style="margin-bottom: 1.5em;">
		<table style="border-collapse: collapse; max-width: 80%; text-align: left; width: 100%;">
			<colgroup style="box-sizing: border-box;">
				<col class="sc-AxheI eXzlnr" style="width: 50%">
				<col class="sc-AxheI eXzlnr" style="width: 50%">
			</colgroup>
			<tr>
				<td style="color:#ffa6c9; font-weight: 700;border-bottom: 1px solid #282828;">Nombre de usuario</td>
				<td style="color:white;border-bottom: 1px solid #282828;"><?php echo $nombre?></td>
			</tr>
			<tr>
				<td style="color:#ffa6c9; font-weight: 700;border-bottom: 1px solid #282828;">Email</td>
				<td style="color:white;border-bottom: 1px solid #282828;"><?php echo $mail?></td>
			</tr>
			<tr>
				<td style="color:#ffa6c9; font-weight: 700;border-bottom: 1px solid #282828;">Tipo de cuenta</td>
				<?php if($_SESSION['usertype'] == 'user'):?>
					<td style="color:white;border-bottom: 1px solid #282828;">Usuario</td>
				<?php else:?>
					<td style="color:white;border-bottom: 1px solid #282828;">Artista</td>
				<?php endif;?>
			</tr>
		</table>
	</section>
</article>
<h4 class='title mb-3' style="padding-top:10px; display: flex;">Tu plan</h4>
<img src="img/plan.jpg" style="max-width: 950px;">
<h5 class='title mb-3' style="padding-top:10px; display: flex; font-size: 16px;">Poyo unlimited</h5>

<h4 class='title mb-3' style="padding-top:10px; display: flex;">Cierra sesión en cualquier parte</h4>
<p style="color:#fff">Cierra sesión donde sea que tengas Poyofy abierto, es decir, en el sitio web, en tu celular, en tu computadora o en cualquier otro dispositivo.</p>
<div class="button1 pl-2" style="display:flex; margin:0; width: 230px; opacity: .8; text-decoration: none;">
		<a class="button" style="text-decoration:none; color:black;" href="includes/logout.inc.php">Cierra sesión en todas partes</a>
</div>
<?php include("includes/footer.php")?>