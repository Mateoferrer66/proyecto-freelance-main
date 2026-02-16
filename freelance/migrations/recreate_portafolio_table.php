<?php
// Drop and recreate portafolio table with all columns
try {
    $db = new PDO('mysql:host=localhost;dbname=freelance', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Drop existing table
    echo "Eliminando tabla portafolio existente...\n";
    $db->exec("DROP TABLE IF EXISTS `portafolio`");
    echo "✅ Tabla eliminada\n\n";
    
    // Create new table with all columns
    echo "Creando tabla portafolio con todas las columnas...\n";
    $sql = "CREATE TABLE `portafolio` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    
    $db->exec($sql);
    echo "✅ Tabla portafolio creada exitosamente\n\n";
    
    // Verify columns
    echo "Verificando columnas creadas:\n";
    echo str_repeat("-", 60) . "\n";
    $result = $db->query('DESCRIBE portafolio');
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("%-20s | %-30s | %s\n", $row['Field'], $row['Type'], $row['Null']);
    }
    
    echo "\n✅ Tabla portafolio lista para usar!\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
