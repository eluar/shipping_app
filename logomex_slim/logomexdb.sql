-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: logomex
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.13.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `choferes`
--

DROP TABLE IF EXISTS `choferes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `choferes` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `choferes`
--

LOCK TABLES `choferes` WRITE;
/*!40000 ALTER TABLE `choferes` DISABLE KEYS */;
/*!40000 ALTER TABLE `choferes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `telefono` int(10) unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'luar','clientiiisimo',4294967295,1),(2,'Rulis','super clientononont chido aca todo cabron',4294967295,1);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contactos`
--

DROP TABLE IF EXISTS `contactos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactos` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `clientes_id` int(9) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `telefono` int(10) unsigned DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `celular` int(10) unsigned DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `comentarios` varchar(250) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  KEY `fk_solicitantes_clientes1` (`clientes_id`),
  CONSTRAINT `fk_solicitantes_clientes1` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactos`
--

LOCK TABLES `contactos` WRITE;
/*!40000 ALTER TABLE `contactos` DISABLE KEYS */;
/*!40000 ALTER TABLE `contactos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `envios`
--

DROP TABLE IF EXISTS `envios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `envios` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `numero_servicio` int(9) DEFAULT NULL,
  `fecha_hora_solicitud` datetime NOT NULL,
  `clientes_id` int(11) NOT NULL,
  `segmento_id` int(11) DEFAULT NULL,
  `subsegmento_id` int(11) DEFAULT NULL,
  `contactos_id` int(9) NOT NULL,
  `ubicaciones_origen_id` int(11) DEFAULT NULL,
  `ubicaciones_destino_id` int(11) DEFAULT NULL,
  `fecha_hora_servicio` datetime NOT NULL,
  `folio` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `vehiculos_id` int(10) NOT NULL,
  `choferes_id` int(9) NOT NULL,
  `observaciones` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `statuses_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_envios_clientes1` (`clientes_id`),
  KEY `fk_envios_segmento1` (`segmento_id`),
  KEY `fk_envios_subsegmento1` (`subsegmento_id`),
  KEY `fk_envios_ubicaciones1` (`ubicaciones_origen_id`),
  KEY `fk_envios_ubicaciones2` (`ubicaciones_destino_id`),
  KEY `fk_envios_statuses1` (`statuses_id`),
  KEY `fk_envios_choferes1` (`choferes_id`),
  KEY `fk_envios_contactos1` (`contactos_id`),
  KEY `fk_envios_vehiculos1` (`vehiculos_id`),
  CONSTRAINT `fk_envios_choferes1` FOREIGN KEY (`choferes_id`) REFERENCES `choferes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_clientes1` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_contactos1` FOREIGN KEY (`contactos_id`) REFERENCES `contactos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_segmento1` FOREIGN KEY (`segmento_id`) REFERENCES `segmento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_statuses1` FOREIGN KEY (`statuses_id`) REFERENCES `statuses` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_subsegmento1` FOREIGN KEY (`subsegmento_id`) REFERENCES `subsegmento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_ubicaciones1` FOREIGN KEY (`ubicaciones_origen_id`) REFERENCES `ubicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_ubicaciones2` FOREIGN KEY (`ubicaciones_destino_id`) REFERENCES `ubicaciones` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_envios_vehiculos1` FOREIGN KEY (`vehiculos_id`) REFERENCES `vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `envios`
--

LOCK TABLES `envios` WRITE;
/*!40000 ALTER TABLE `envios` DISABLE KEYS */;
/*!40000 ALTER TABLE `envios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `segmento`
--

DROP TABLE IF EXISTS `segmento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `segmento` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `clientes_id` int(9) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_segmento_clientes` (`clientes_id`),
  CONSTRAINT `fk_segmento_clientes` FOREIGN KEY (`clientes_id`) REFERENCES `clientes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `segmento`
--

LOCK TABLES `segmento` WRITE;
/*!40000 ALTER TABLE `segmento` DISABLE KEYS */;
/*!40000 ALTER TABLE `segmento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_key` varchar(20) NOT NULL,
  `descripcion` varchar(115) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `statuses`
--

LOCK TABLES `statuses` WRITE;
/*!40000 ALTER TABLE `statuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subsegmento`
--

DROP TABLE IF EXISTS `subsegmento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subsegmento` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `segmento_id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subsegmento_segmento1` (`segmento_id`),
  CONSTRAINT `fk_subsegmento_segmento1` FOREIGN KEY (`segmento_id`) REFERENCES `segmento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subsegmento`
--

LOCK TABLES `subsegmento` WRITE;
/*!40000 ALTER TABLE `subsegmento` DISABLE KEYS */;
/*!40000 ALTER TABLE `subsegmento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_usuario`
--

DROP TABLE IF EXISTS `tipo_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_usuario` varchar(25) NOT NULL,
  `descripcion` varchar(115) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_usuario`
--

LOCK TABLES `tipo_usuario` WRITE;
/*!40000 ALTER TABLE `tipo_usuario` DISABLE KEYS */;
INSERT INTO `tipo_usuario` VALUES (1,'Super Usuario',''),(2,'Administrador',NULL);
/*!40000 ALTER TABLE `tipo_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_vehiculos`
--

DROP TABLE IF EXISTS `tipo_vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_vehiculos` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `key_tipo_unidad` varchar(35) NOT NULL,
  `descripcion` varchar(115) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_unidad_UNIQUE` (`key_tipo_unidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_vehiculos`
--

LOCK TABLES `tipo_vehiculos` WRITE;
/*!40000 ALTER TABLE `tipo_vehiculos` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_vehiculos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ubicaciones` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `key_tipo` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicaciones`
--

LOCK TABLES `ubicaciones` WRITE;
/*!40000 ALTER TABLE `ubicaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `ubicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(15) NOT NULL,
  `tipo_usuario_id` int(11) NOT NULL,
  `user_password` varchar(15) NOT NULL,
  `user_real_name` varchar(65) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name_UNIQUE` (`user_name`),
  KEY `fk_usuario_s_tipo_usuario1` (`tipo_usuario_id`),
  CONSTRAINT `fk_usuario_s_tipo_usuario1` FOREIGN KEY (`tipo_usuario_id`) REFERENCES `tipo_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'bren',2,'Bulls','La Bren',0),(2,'luar',1,'Berenice7','El Luar',1),(3,'Diarrea',2,'bully','Tu Cola',0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `tipo_vehiculos_id` int(9) NOT NULL,
  `numero` int(11) NOT NULL,
  `economico` varchar(20) NOT NULL,
  `placas` varchar(20) NOT NULL,
  `serie` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `nip` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_economicos_tipo_vehiculos1` (`tipo_vehiculos_id`),
  CONSTRAINT `fk_economicos_tipo_vehiculos1` FOREIGN KEY (`tipo_vehiculos_id`) REFERENCES `tipo_vehiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-14  9:29:12
