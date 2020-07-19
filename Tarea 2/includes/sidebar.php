<div class="wrapper">
	<nav class="sidebar">
		<div class="sidebar-header">
			<img src="img/poyofy.png" alt="poyofy" width=150px>
		</div>
		<div class="sidebar-nav">
			<div class="nav-item">
				<a href="index.php" class="nav-link">
					<i class="fas fa-home"></i>
					<span>Inicio</span>
				</a>
			</div>
			<div class="nav-item">
				<a href="#" class="nav-link">
					<i class="fas fa-search"></i>
					<span>Buscar</span>
				</a>
			</div>
			<!-- Muestra contenido segun el tipo de usuario-->
			<?php if($_SESSION['usertype'] == 'user'):?>
				<div class="nav-create d-flex flex-column mt-4">
					<h2>Playlists</h2>
					<div class="nav-item">
						<a href="add_playlist.php" class="nav-link">
							<i class="fas fa-plus-circle"></i>
							<span>Nuevo playlist</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="#" class="nav-link">
							<i class="fas fa-heart"></i>
							<span>Canciones favoritas</span>
						</a>
					</div>
				</div>

			<?php else: ?>
				<div class="nav-create d-flex flex-column mt-4">
					<h2>Studio</h2>
					<div class="nav-item">
						<a href="#" class="nav-link">
							<i class="fas fa-music"></i>
							<span>Nueva canción</span>
						</a>
					</div>
					<div class="nav-item">
						<a href="#" class="nav-link">
							<i class="fas fa-plus-circle"></i>
							<span>Nuevo album</span>
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</nav>
</div>
