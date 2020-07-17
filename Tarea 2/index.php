<?php 
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
	<title>Poyofy - Poyo para tod@s</title>
	<link rel="stylesheet" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>
	<header>
		<div class="main-container">
			<div class="topbar">

			</div>
			<div class="wrapper">
				<nav class="sidebar">
					<div class="sidebar-header">
						<img src="img/poyofy.png" alt="poyofy" width=150px>
					</div>

					<div class="sidebar-nav">
						<div class="nav-item">
						 		<a href="#" class="nav-link">
						 			<i class="fas fa-home"></i>
						 			<span>Inicio</span>
	                				
	                			</a>
	           			</div>
	           			<div class="nav-item">
						 	<div> 
	                			<a href="#" class="nav-link">
	                				<i class="fas fa-search"></i>
	                				<span>Buscar</span>
	                			</a>
	           			 	</div>
	           			</div>

	            		<div class="nav-item">
						 	<div> 
	                			<a href="#" class="nav-link">
	                				<i class="far fa-bookmark"></i>
	                				<span>Tu biblioteca</span>
	                			</a>
	           			 	</div>
	           			</div>

	           			<!-- Muestra contenido segun el tipo de usuario OJO mal echo de tipo de usuario :C-->
	           			<?php if($_SESSION['usertype'] == 'user'):?>
	           				<div class="nav-create d-flex flex-column mt-4">
	           					<h2>Playlists</h2>
	           				</div>
	           			<?php else: ?>
	           				<div class="nav-create d-flex flex-column mt-4">
	           					<h2>Studio</h2>
	           					<div class="nav-item">
						 			<div> 
	                					<a href="#" class="nav-link">
	                						<i class="fas fa-music"></i>
	                						<span>Nueva canción</span>
	                					</a>
	           			 			</div>
	           					</div>
	           					<div class="nav-item">
						 			<div> 
	                					<a href="#" class="nav-link">
	                						<i class="fas fa-plus-circle"></i>
	                						<span>Nuevo album</span>
	                					</a>
	           			 			</div>
	           					</div>
	           				</div>
	           			<?php endif; ?>

					</div>


				</nav>
			</div>

			 
    </div>
  		</div>
		</div>

	</header>

</body>
</html>