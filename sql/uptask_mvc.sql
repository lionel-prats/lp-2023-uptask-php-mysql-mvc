-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.25-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para uptask_mvc
CREATE DATABASE IF NOT EXISTS `uptask_mvc` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `uptask_mvc`;

-- Volcando estructura para tabla uptask_mvc.proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proyecto` varchar(60) DEFAULT NULL,
  `url` varchar(32) DEFAULT NULL,
  `propietarioId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `propietarioId` (`propietarioId`),
  CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`propietarioId`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla uptask_mvc.proyectos: ~6 rows (aproximadamente)
INSERT INTO `proyectos` (`id`, `proyecto`, `url`, `propietarioId`) VALUES
	(1, 'Integralnet', '1045124382c2f36a823436dc0977dbb5', 1),
	(2, 'Protocolos cruzados', '7d6694e958fb37f98d61e81421c489e4', 1),
	(3, 'API Python', '897bd3c7bfe6f1329439b7c6da04fefc', 4),
	(4, 'SC', '861b93aa4f2aeebcf7986503dbb8fbbe', 2),
	(5, '3.14', '81cda3f3bb0e9ab6e150b305d3adcf2d', 2),
	(6, 'Julio 2023', '42f4716f6313dbde3732c555198c7dd3', 1);

-- Volcando estructura para tabla uptask_mvc.tareas
CREATE TABLE IF NOT EXISTS `tareas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `proyectoId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proyectoId` (`proyectoId`),
  CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`proyectoId`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla uptask_mvc.tareas: ~7 rows (aproximadamente)
INSERT INTO `tareas` (`id`, `nombre`, `estado`, `proyectoId`) VALUES
	(1, 'Sincronización de protocolos', 0, 1),
	(2, 'Revisar WS', 0, 2),
	(3, 'Blurear caras', 0, 1),
	(4, 'Diseñar base de datos', 0, 3),
	(5, 'Listar registros editados', 0, 4),
	(6, 'Limpiar scripts en desuso', 0, 5),
	(7, 'Chequear conexión a BD', 0, 5);

-- Volcando estructura para tabla uptask_mvc.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `confirmado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Volcando datos para la tabla uptask_mvc.usuarios: ~5 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `token`, `confirmado`) VALUES
	(1, ' Lionel', 'lionel@correo.com', '$2y$10$nsIVXxl6rYSms1BH1LrJnuYCh2w52RMNHfjgOeoE0xcFVSajYUvgC', '', 1),
	(2, 'Alex', 'alex@correo.com', '$2y$10$nsIVXxl6rYSms1BH1LrJnuYCh2w52RMNHfjgOeoE0xcFVSajYUvgC', '', 1),
	(3, 'Rafael', 'rafael@correo.com', '$2y$10$BDSFhOxjdsPvmKv0Tbh1euxVeLr6xu6uwrkVGXVmTk6KwCI3Dpujq', '', 1),
	(4, 'Tomás', 'tomas@correo.com', '$2y$10$nsIVXxl6rYSms1BH1LrJnuYCh2w52RMNHfjgOeoE0xcFVSajYUvgC', '', 1),
	(5, 'Luis', 'luis@correo.com', '$2y$10$nsIVXxl6rYSms1BH1LrJnuYCh2w52RMNHfjgOeoE0xcFVSajYUvgC', '', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
