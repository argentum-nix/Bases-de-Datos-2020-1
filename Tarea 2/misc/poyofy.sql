-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-08-2020 a las 15:48:23
-- Versión del servidor: 10.4.13-MariaDB
-- Versión de PHP: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `poyofy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albumes`
--

CREATE TABLE `albumes` (
  `id_album` int(11) NOT NULL,
  `id_artista` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `debut_year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Disparadores `albumes`
--
DELIMITER $$
CREATE TRIGGER `delete_album` AFTER DELETE ON `albumes` FOR EACH ROW BEGIN
	DELETE FROM canciones_albumes WHERE id_album = OLD.id_album;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `artistas`
--

CREATE TABLE `artistas` (
  `id_artista` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `artist_follows`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `artist_follows` (
`id_persona` int(11)
,`id_artista` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canciones`
--

CREATE TABLE `canciones` (
  `id_cancion` int(11) NOT NULL,
  `id_artista` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `duracion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Disparadores `canciones`
--
DELIMITER $$
CREATE TRIGGER `delete_cancion` AFTER DELETE ON `canciones` FOR EACH ROW BEGIN
	DELETE FROM canciones_albumes WHERE id_cancion = OLD.id_cancion;
	DELETE FROM likes_canciones WHERE id_cancion = OLD.id_cancion;
	DELETE FROM canciones_playlists WHERE id_cancion = OLD.id_cancion;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canciones_albumes`
--

CREATE TABLE `canciones_albumes` (
  `id_cancion` int(11) NOT NULL,
  `id_album` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canciones_playlists`
--

CREATE TABLE `canciones_playlists` (
  `id_cancion` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `follows`
--

CREATE TABLE `follows` (
  `id_persona1` int(11) NOT NULL,
  `id_persona2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `follow_playlists`
--

CREATE TABLE `follow_playlists` (
  `id_persona` int(11) NOT NULL,
  `id_playlist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes_canciones`
--

CREATE TABLE `likes_canciones` (
  `id_cancion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `likes_view`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `likes_view` (
`id_usuario` int(11)
,`nombre_cancion` varchar(40)
,`nombre_artista` varchar(40)
,`duracion` int(11)
,`id_cancion` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `mail` varchar(40) NOT NULL,
  `password` varchar(74) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Disparadores `personas`
--
DELIMITER $$
CREATE TRIGGER `delete_account` AFTER DELETE ON `personas` FOR EACH ROW BEGIN
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
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `playlists`
--

CREATE TABLE `playlists` (
  `id_playlist` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Disparadores `playlists`
--
DELIMITER $$
CREATE TRIGGER `delete_playlist` AFTER DELETE ON `playlists` FOR EACH ROW BEGIN
	DELETE FROM follow_playlists WHERE id_playlist = OLD.id_playlist;
    DELETE FROM canciones_playlists WHERE 
    id_playlist = OLD.id_playlist;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `play_follows`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `play_follows` (
`id_persona` int(11)
,`id_playlist` int(11)
,`nombre` varchar(40)
,`nombre_usuario` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `user_follows`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `user_follows` (
`id_persona` int(11)
,`id_usuario` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_album`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_album` (
`nombre_cancion` varchar(40)
,`duracion` int(11)
,`nombre_artista` varchar(40)
,`debut_year` int(11)
,`id_album` int(11)
,`nombre_album` varchar(40)
,`id_artista` int(11)
,`id_cancion` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_cancion`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_cancion` (
`id_cancion` int(11)
,`id_artista` int(11)
,`duracion` int(11)
,`nombre_artista` varchar(40)
,`nombre_cancion` varchar(40)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_playlist`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_playlist` (
`nombre_cancion` varchar(40)
,`duracion` int(11)
,`id_usuario` int(11)
,`nombre_artista` varchar(40)
,`id_playlist` int(11)
,`nombre_playlist` varchar(40)
,`id_cancion` int(11)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `artist_follows`
--
DROP TABLE IF EXISTS `artist_follows`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `artist_follows`  AS  select `P`.`id_persona` AS `id_persona`,`A`.`id_artista` AS `id_artista` from ((`personas` `P` join `artistas` `A`) join `follows` `F`) where `P`.`id_persona` = `F`.`id_persona1` and `F`.`id_persona2` = `A`.`id_artista` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `likes_view`
--
DROP TABLE IF EXISTS `likes_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `likes_view`  AS  select `LC`.`id_usuario` AS `id_usuario`,`C`.`nombre` AS `nombre_cancion`,`P`.`nombre` AS `nombre_artista`,`C`.`duracion` AS `duracion`,`C`.`id_cancion` AS `id_cancion` from ((`likes_canciones` `LC` join `canciones` `C`) join `personas` `P`) where `LC`.`id_cancion` = `C`.`id_cancion` and `C`.`id_artista` = `P`.`id_persona` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `play_follows`
--
DROP TABLE IF EXISTS `play_follows`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `play_follows`  AS  select `FP`.`id_persona` AS `id_persona`,`FP`.`id_playlist` AS `id_playlist`,`P`.`nombre` AS `nombre`,`PR`.`nombre` AS `nombre_usuario` from ((`playlists` `P` join `follow_playlists` `FP`) join `personas` `PR`) where `P`.`id_playlist` = `FP`.`id_playlist` and `P`.`id_usuario` = `PR`.`id_persona` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `user_follows`
--
DROP TABLE IF EXISTS `user_follows`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_follows`  AS  select `P`.`id_persona` AS `id_persona`,`U`.`id_usuario` AS `id_usuario` from ((`personas` `P` join `usuarios` `U`) join `follows` `F`) where `P`.`id_persona` = `F`.`id_persona1` and `F`.`id_persona2` = `U`.`id_usuario` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_album`
--
DROP TABLE IF EXISTS `vista_album`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_album`  AS  select `C`.`nombre` AS `nombre_cancion`,`C`.`duracion` AS `duracion`,`P`.`nombre` AS `nombre_artista`,`A`.`debut_year` AS `debut_year`,`A`.`id_album` AS `id_album`,`A`.`nombre` AS `nombre_album`,`C`.`id_artista` AS `id_artista`,`C`.`id_cancion` AS `id_cancion` from (((`canciones_albumes` `CA` join `canciones` `C`) join `personas` `P`) join `albumes` `A`) where `A`.`id_album` = `CA`.`id_album` and `CA`.`id_cancion` = `C`.`id_cancion` and `C`.`id_artista` = `P`.`id_persona` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_cancion`
--
DROP TABLE IF EXISTS `vista_cancion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_cancion`  AS  select `C`.`id_cancion` AS `id_cancion`,`C`.`id_artista` AS `id_artista`,`C`.`duracion` AS `duracion`,`P`.`nombre` AS `nombre_artista`,`C`.`nombre` AS `nombre_cancion` from (`canciones` `C` join `personas` `P` on(`C`.`id_artista` = `P`.`id_persona`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_playlist`
--
DROP TABLE IF EXISTS `vista_playlist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_playlist`  AS  select `C`.`nombre` AS `nombre_cancion`,`C`.`duracion` AS `duracion`,`PL`.`id_usuario` AS `id_usuario`,`P`.`nombre` AS `nombre_artista`,`PL`.`id_playlist` AS `id_playlist`,`PL`.`nombre` AS `nombre_playlist`,`C`.`id_cancion` AS `id_cancion` from (((`canciones_playlists` `CP` join `canciones` `C`) join `personas` `P`) join `playlists` `PL`) where `PL`.`id_playlist` = `CP`.`id_playlist` and `CP`.`id_cancion` = `C`.`id_cancion` and `C`.`id_artista` = `P`.`id_persona` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `albumes`
--
ALTER TABLE `albumes`
  ADD PRIMARY KEY (`id_album`),
  ADD KEY `id_artista` (`id_artista`);

--
-- Indices de la tabla `artistas`
--
ALTER TABLE `artistas`
  ADD PRIMARY KEY (`id_artista`);

--
-- Indices de la tabla `canciones`
--
ALTER TABLE `canciones`
  ADD PRIMARY KEY (`id_cancion`),
  ADD KEY `id_artista` (`id_artista`);

--
-- Indices de la tabla `canciones_albumes`
--
ALTER TABLE `canciones_albumes`
  ADD PRIMARY KEY (`id_cancion`,`id_album`);

--
-- Indices de la tabla `canciones_playlists`
--
ALTER TABLE `canciones_playlists`
  ADD PRIMARY KEY (`id_cancion`,`id_playlist`);

--
-- Indices de la tabla `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id_persona1`,`id_persona2`);

--
-- Indices de la tabla `follow_playlists`
--
ALTER TABLE `follow_playlists`
  ADD PRIMARY KEY (`id_persona`,`id_playlist`);

--
-- Indices de la tabla `likes_canciones`
--
ALTER TABLE `likes_canciones`
  ADD PRIMARY KEY (`id_cancion`,`id_usuario`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id_playlist`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `albumes`
--
ALTER TABLE `albumes`
  MODIFY `id_album` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `canciones`
--
ALTER TABLE `canciones`
  MODIFY `id_cancion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id_playlist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `albumes`
--
ALTER TABLE `albumes`
  ADD CONSTRAINT `albumes_ibfk_1` FOREIGN KEY (`id_artista`) REFERENCES `artistas` (`id_artista`);

--
-- Filtros para la tabla `canciones`
--
ALTER TABLE `canciones`
  ADD CONSTRAINT `canciones_ibfk_1` FOREIGN KEY (`id_artista`) REFERENCES `artistas` (`id_artista`);

--
-- Filtros para la tabla `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
