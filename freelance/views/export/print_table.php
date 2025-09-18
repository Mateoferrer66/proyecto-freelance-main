<?php
/** @var string $titulo */
/** @var array $headers */
/** @var array $rows */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $titulo ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print" style="text-align:right;">
    <button onclick="window.print()">🖨️ Imprimir</button>
</div>

<h2><?= $titulo ?></h2>

<table>
    <thead>
        <tr>
            <?php foreach ($headers as $header): ?>
                <th><?= $header ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td><?= $cell ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>