


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table carros
# ------------------------------------------------------------

DROP TABLE IF EXISTS `carros`;

CREATE TABLE `carros` (
  `id_carro` int NOT NULL AUTO_INCREMENT,
  `tracking` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fk_user` int DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `empaque` varchar(250) DEFAULT NULL,
  `parts` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_carro`),
  KEY `fk_user` (`fk_user`),
  CONSTRAINT `carros_ibfk_1` FOREIGN KEY (`fk_user`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `carros` WRITE;
/*!40000 ALTER TABLE `carros` DISABLE KEYS */;

INSERT INTO `carros` (`id_carro`, `tracking`, `fk_user`, `empaque`, `parts`) VALUES
	(71, '00000', 1, '/uploads/2023/08/00000/00000-Box.jpg', NULL),
	(72, '0101', 1, '/uploads/2023/08/0101/0101-Box.jpg', NULL),
	(73, '11111', 1, '/uploads/2023/08/11111/11111-Box.jpg', NULL),
	(74, '2324', 1, '/uploads/2023/08/2324/2324-Box.jpg', '/uploads/2023/08/2324/2324-SP.jpg'),
	(75, '3123', 1, '/uploads/2023/08/3123/3123-Box.jpg', '/uploads/2023/08/3123/3123-SP.jpg'),
	(76, '431414', 1, '/uploads/2023/08/431414/431414-Box.jpg', '/uploads/2023/08/431414/431414-SP.jpg'),
	(77, '898', 1, '/uploads/2023/08/898/898-Box.jpg', '/uploads/2023/08/898/898-SP.jpg'),
	(78, '23232', 1, '/uploads/2023/08/23232/23232-Box.jpg', '/uploads/2023/08/23232/23232-SP.jpg');

/*!40000 ALTER TABLE `carros` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table componentes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `componentes`;

CREATE TABLE `componentes` (
  `id_componente` int NOT NULL AUTO_INCREMENT,
  `fk_carro` int DEFAULT NULL,
  `componente` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_componente`),
  KEY `fk_carro` (`fk_carro`),
  CONSTRAINT `componentes_ibfk_1` FOREIGN KEY (`fk_carro`) REFERENCES `carros` (`id_carro`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;





# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id_rol`, `descripcion`) VALUES
	(1, 'Administrador'),
	(2, 'Supervisor'),
	(3, 'Control de Calidad');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `fk_rol` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estacion` int DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `fk_users_roles` (`fk_rol`),
  CONSTRAINT `fk_users_roles` FOREIGN KEY (`fk_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `fk_rol`, `nombre`, `apellidos`, `estacion`) VALUES
	(1, '140', 'ucid@allsafeit.com', '$2y$10$YkX72B8Ki3x7IpsBRgjCDer.6jGPsEL0.JuKFq38SlK2FbjLpM/GC', 1, 'Uriel', 'Cid', 10),
	(2, '450010', '450010', '$2y$10$TBmehJtTfSB75ZfYPsf98e4fIuUg6hQpLLl6ItiFY4BDHp0y9DBTy', 3, 'Juan', 'Guarnizo', NULL),
	(3, '81850', '81850', '$2y$10$YkX72B8Ki3x7IpsBRgjCDer.6jGPsEL0.JuKFq38SlK2FbjLpM/GC', 3, 'Abelardo', 'Lemon', NULL),
	(4, '1723', '1723', '$2y$10$JWfhPKlNfR7iLf2S8IUKYO9ABwZnheJUgvHMxfV3Z8vk0/AJKv5Sq', 2, 'Felipe', 'Lopez', NULL);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


