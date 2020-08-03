<?php 
include("includes/header.php");
if(isset($_GET['id']) and isset($_GET['cur'])) {
	$user_id = $_GET['id'];
	$is_current_user = $_GET['cur'];
}
else {
	header("Location: index.php");
	exit();
}

$query_nombre = mysqli_query($connection, "SELECT nombre FROM personas WHERE id_persona='$user_id'");
$fila = mysqli_fetch_row($query_nombre);
$username = $fila[0];

$query_seguidores = mysqli_query($connection, "SELECT COUNT(id_persona1) FROM follows WHERE id_persona2='$user_id'");
$fila = mysqli_fetch_row($query_seguidores);
$seguidores = $fila[0];
?>

<div class="entityinfo">
	<div class="leftsection">
		<img src="img/profile.jpg" style="width: 100%"/>
	</div>
	<div class="rightsection">
		<p style="color:#b3b3b3; font-weight: 500; margin-bottom: 0px;">Perfil de usuario</p>
		<h2 class='title mb-3' style="margin-top: 0px"><?php echo $username ?></h2>
		<p style="color:#b3b3b3; font-weight: 400; margin-top: 90px;"><?php echo $seguidores?> seguidores</p>
	</div>
	<?php
	if(!$is_current_user){
		$curid = $_SESSION['id'];
		$query_follow = mysqli_query($connection, "SELECT * FROM user_follows WHERE id_persona='$curid' AND id_usuario='$user_id'");
		$fila = mysqli_fetch_row($query_follow);
		// Usuario actual (sea este artista o usuario normal) ya sigue a artista de este perfil
		if($fila){
			echo
			"<form action='includes/follow.inc.php' method='post'>
				<input type='hidden' name='current' value='".$_SESSION['id']."'>
				<input type='hidden' name='is_cur' value='".$is_current_user."'>
				<input type='hidden' name='to-unfollow' value='".$user_id."'>
				<button class='x-button' name='unfollow_user' type='submit'>Dejar de seguir</button>
			</form>";
		}
		else{
			echo
			"<form action='includes/follow.inc.php' method='post'>
				<input type='hidden' name='current' value='".$_SESSION['id']."'>
				<input type='hidden' name='is_cur' value='".$is_current_user."'>
				<input type='hidden' name='to-follow' value='".$user_id."'>
				<button class='x-button' name='follow_user' type='submit'>Seguir</button>
			</form>";
		}
	}
	?>
</div>

<h2 class='title mb-3' style="margin-top: 0px">Playlists</h2>
<div class="row list mb-5">
		<?php
		$res = mysqli_query($connection, "SELECT id_playlist, nombre FROM playlists WHERE id_usuario ='$user_id' LIMIT 12");
		$flag = true;
		while($fila1 = mysqli_fetch_array($res)){
			$flag = false;
			echo
			"
			<div class='col-12 col-md-3 col-lg-2'>
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
				echo "<p style='color:#b3b3b3; padding-left:5px;'>Todavía no hiciste una playlist.</p>";
			}
			else{
				echo "<p style='color:#b3b3b3; padding-left:5px;'>Usuario todavía no creó una playlist.</p>";
			}
		}
		?>
	</div>

<?php
echo 
	"<a style='text-decoration: none; color:#b3b3b3;text-align:right;'
		href='user_profile_playlists.php?id=".$user_id."&&name=".$username."&&cur=".$is_current_user."'>
		<p style='padding-right:50px;'>VER MÁS</p>
	</a>"
?>

<?php include("includes/footer.php")?>