<?php 
include("includes/header.php");
?>

<!--CREATE VIEW as
SELECT C.nombre, P.nombre FROM likes_canciones LC, canciones C, personas P WHERE LC.id_cancion = C.id_cancion AND LC.id_usuario = 8 AND C.id_artista = P.id_persona GROUP BY C.id_cancion-->

<?php include("includes/footer.php")?>