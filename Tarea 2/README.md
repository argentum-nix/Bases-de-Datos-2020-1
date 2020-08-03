# Poyofy - clon de Spotify, pero rosado
![](https://i.redd.it/numywxxyp2p21.jpg)

Tarea 2 de ramo *Bases de Datos: INF 239*.  
Universidad Técnica Santa María  
2020-1

# Datos
- Anastasiia Fedorova  201873505-1  
<anastasiia.fedorova@sansano.usm.cl>

# Dependencias y Stack
- PHP 7.4.8
- MySQL
- Javascript
- HTML
- CSS
- XAMPP para montar la máquina virtual y configurar localhost
- Sistema principal: MacOS Sierra 10.12.6

# Ejecución
- El contenido del repositorio debe estar ubicado en la carpeta T2 de htdocs
- Al acceder a cualquier página (ej. localhost:8080/T2/index.php) el usuario será redireccionado a la página de Log In o Sign Up, según el caso.

# Registración
Existen dos tipos de cuentas - cuenta Usuario o cuenta Artista. Para elegir el tipo de la cuenta, se debe utilizar el select de menu de registración. El tipo de cuenta es permanenente y no puede ser cambiado más tarde.
![alt text](misc/register.png)
![alt text](misc/register_usertype.png)

# Sesión de usuario
La sesión de usuario se inicializa a partir de login.php, una vez ingresados bien los datos según la base de datos.
Para cerrar la sesión, se puede utilizar el menu dropdown el topbar persistente, el botón en la sección Cuenta o al borrar la cuenta - en este caso, se destruye toda la información relacionada con el usuario (likes, playlists, canciones, etc).

# Barra de navegación superior
Permite acceder a vistas de usuarios seguidos, artistas seguidos o playlists seguidos. Cualquier usuario tiene estas vistas disponibles.
![alt text](misc/followed_users.png)
![alt text](misc/followed_artists.png)
![alt text](misc/followed_playlists.png)

# Busqueda

# Creación de playlists, albumes y canciones

# Canciones favoritas 

# Redacción de playlists, canciones y albumes

# Perfíl de usuario y seguimiento
