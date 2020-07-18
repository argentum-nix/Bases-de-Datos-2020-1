<div class="topbar">
	<div class="dropdown menu ml-3"style="width: 100px; position: absolute; right:3%; margin-right: 28px; top: 10px;">
		<button type="button" class="d-flex align-items-center" data-toggle="dropdown">
			<?php if($_SESSION['usertype'] == 'user'):?>
				<img src="img/user_profile.png" class="profile" width=150px>
			<?php else: ?>
				<img src="img/artist_profile.png" class="profile" width=150px>
			<?php endif; ?>
			<span><?php echo $_SESSION['nombre'];?></span>
				<i class="fas fa-caret-down ml-2 mr-2"></i>
		</button>
		<div class="dropdown-menu mt-0 p-0">
				<a href="#" class="dropdown-item">Cuenta</a>
				<a href="#" class="dropdown-item">Perfil</a>
				<div class="dropdown-divider"></div>
				<a href="#" class="dropdown-item">Salir</a>
		</div>
	</div>
</div>