<?php
// Verify portafolio table structure
try {
    $db = new PDO('mysql:host=localhost;dbname=freelance', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Columnas de la tabla portafolio:\n";
    echo str_repeat("-", 50) . "\n";
    
    $result = $db->query('DESCRIBE portafolio');
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("%-20s | %-30s\n", $row['Field'], $row['Type']);
    }
    
    echo "\n";
    
    // Count records
    $count = $db->query('SELECT COUNT(*) FROM portafolio')->fetchColumn();
    echo "Total de registros: $count\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
