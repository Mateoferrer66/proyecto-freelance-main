<?php
$db = new PDO('mysql:host=localhost;dbname=freelance', 'root', 'root');
$stmt = $db->query("SHOW TABLES LIKE 'portafolio'");
$result = $stmt->fetchAll();
if (count($result) > 0) {
    echo "TABLE EXISTS\n";
    $cols = $db->query('DESCRIBE portafolio');
    foreach ($cols as $col) {
        echo $col['Field'] . ' | ' . $col['Type'] . ' | ' . $col['Null'] . ' | ' . $col['Key'] . "\n";
    }
} else {
    echo "TABLE DOES NOT EXIST\n";
}
