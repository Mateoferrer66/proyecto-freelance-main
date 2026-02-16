<?php
$log = file_get_contents('runtime/logs/app.log');
$lines = explode("\n", $log);
$output = [];
foreach ($lines as $i => $line) {
    if (stripos($line, 'Undefined array key') !== false) {
        $start = max(0, $i - 2);
        $end = min(count($lines) - 1, $i + 25);
        for ($j = $start; $j <= $end; $j++) {
            $output[] = "L$j: " . $lines[$j];
        }
        $output[] = "---";
    }
}
if (empty($output)) {
    $output[] = "NOT FOUND in log.";
}
file_put_contents('error_trace.txt', implode("\n", $output));
echo "Done. Written " . count($output) . " lines to error_trace.txt\n";
