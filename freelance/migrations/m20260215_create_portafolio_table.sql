-- Migration script to create portafolio table
-- Run this against the freelance database

CREATE TABLE `portafolio` (
  `por_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código identificador del registro',
  `soc_id` bigint(20) NOT NULL COMMENT 'Código del socio',
  `por_titulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Título del proyecto',
  `por_descripcion` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripción del proyecto',
  `por_imagenes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Imágenes del proyecto (rutas separadas por coma)',
  `por_eliminado` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Campo para indicar si el registro está eliminado: 0 - No, 1 - Si',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`por_id`),
  KEY `fk_portafolio_socio` (`soc_id`),
  CONSTRAINT `fk_portafolio_socio` FOREIGN KEY (`soc_id`) REFERENCES `socio` (`soc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
