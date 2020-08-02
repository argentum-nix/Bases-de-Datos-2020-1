
Vista para tener todas las canciones del album, 
con los nombres, duraciones, nombre de album de cuestion y 
su año de debut.


CREATE VIEW vista_album as
							SELECT C.nombre as nombre_cancion,
							C.duracion,
							P.nombre as nombre_artista,
							A.debut_year,
							A.id_album,
							A.nombre as nombre_album,
							C.id_artista
							FROM canciones_albumes CA, canciones C, personas P, albumes A
							WHERE A.id_album = CA.id_album 
							AND CA.id_cancion = C.id_cancion
							AND C.id_artista = P.id_persona

Misma idea, ahora para playlists.
CREATE VIEW vista_playlist AS
							SELECT 
							C.nombre as nombre_cancion,
							C.duracion,
							PL.id_usuario,
							P.nombre as nombre_artista,
							PL.id_playlist,
							PL.nombre as nombre_playlist
							FROM canciones_playlists CP, canciones C, personas P, playlists PL
							WHERE PL.id_playlist = CP.id_playlist 
							AND CP.id_cancion = C.id_cancion
							AND C.id_artista = P.id_persona


Misma idea, para canción:

CREATE VIEW vista_cancion AS 
							SELECT C.id_cancion,C.id_artista,
							 C.duracion, P.nombre as nombre_artista,
							C.nombre as nombre_cancion FROM canciones C
							INNER JOIN personas P ON C.id_artista=P.id_persona




Vista para tener todos los usuarios a los que siguen N personas de la BD:

CREATE VIEW user_follows as
				SELECT P.id_persona, U.id_usuario
				FROM personas P, usuarios U, follows F 
                                WHERE P.id_persona = F.id_persona1
                                AND F.id_persona2 = U.id_usuario

Vista para tener todas las artistas a los que siguen las N personas de la BD:

CREATE VIEW artist_follows as
				SELECT P.id_persona,  A.id_artista
				FROM personas P, artistas A, follows F 
                                WHERE P.id_persona = F.id_persona1
                                AND F.id_persona2 = A.id_artista


Vista para tener todos los likes, canciones y usuarios que dieron like 

CREATE VIEW likes_view as
				SELECT LC.id_usuario, C.nombre as nombre_cancion, P.nombre as nombre_artista, C.duracion
				FROM likes_canciones LC, canciones C, personas P 
				WHERE LC.id_cancion = C.id_cancion 
				AND C.id_artista = P.id_persona

CREATE VIEW play_follows as 
				SELECT FP.id_persona, FP.id_playlist, P.nombre, PR.nombre as nombre_usuario
				FROM playlists P, follow_playlists FP, personas PR
				WHERE P.id_playlist = FP.id_playlist
				AND P.id_usuario = PR.id_persona


DELIMITER $$
CREATE TRIGGER delete_playlist
AFTER DELETE ON playlists
FOR EACH ROW 
BEGIN
	DELETE FROM follow_playlists WHERE id_playlist = OLD.id_playlist;
END$$

DELIMITER $$
CREATE TRIGGER delete_album
AFTER DELETE ON albumes
FOR EACH ROW 
BEGIN
	DELETE FROM canciones_albumes WHERE id_album = OLD.id_album;
END$$
DELIMITER $$

CREATE TRIGGER delete_cancion
AFTER DELETE ON canciones
FOR EACH ROW 
BEGIN
	DELETE FROM canciones_albumes WHERE id_cancion = OLD.id_cancion;
	DELETE FROM likes_canciones WHERE id_cancion = OLD.id_cancion;
	DELETE FROM canciones_playlists WHERE id_cancion = OLD.id_cancion;
END$$

DELIMITER $$
CREATE TRIGGER delete_account
AFTER DELETE ON personas
FOR EACH ROW
BEGIN
	IF EXISTS(SELECT * FROM usuarios WHERE id_usuario = OLD.id_persona) THEN
        BEGIN
            DELETE FROM likes_canciones WHERE id_usuario = OLD.id_persona;
            DELETE FROM follow_playlists WHERE id_persona = OLD.id_persona;
            DELETE FROM follows WHERE id_persona1 = OLD.id_persona OR id_persona2 = OLD.id_persona;
            DELETE FROM playlists WHERE id_usuario = OLD.id_persona;
 	    DELETE FROM usuarios WHERE id_usuario = OLD.id_persona;

        END;
    ELSE     
	BEGIN
             DELETE FROM albumes WHERE id_artista = OLD.id_persona;
             DELETE FROM canciones WHERE id_artista = OLD.id_persona;
	     DELETE FROM follow_playlists WHERE id_persona = OLD.id_persona;
             DELETE FROM follows WHERE id_persona1 = OLD.id_persona OR id_persona2 = OLD.id_persona;
             DELETE FROM artistas WHERE id_artista = OLD.id_persona;
        END;
   END IF;
END$$


