<?php
$log = file_get_contents('runtime/logs/app.log');
// Find all occurrences of "Undefined array key" 
$lines = explode("\n", $log);
$found = false;
foreach ($lines as $i => $line) {
    if (stripos($line, 'Undefined array key') !== false) {
        $found = true;
        // Print surrounding context (20 lines before and after)
        $start = max(0, $i - 5);
        $end = min(count($lines) - 1, $i + 30);
        echo "=== Found at line $i ===\n";
        for ($j = $start; $j <= $end; $j++) {
            echo $lines[$j] . "\n";
        }
        echo "========================\n\n";
    }
}
if (!$found) {
    echo "No 'Undefined array key' found in the log.\n";
    // Show the last 50 lines
    echo "=== Last 50 lines ===\n";
    $lastLines = array_slice($lines, -50);
    echo implode("\n", $lastLines);
}
