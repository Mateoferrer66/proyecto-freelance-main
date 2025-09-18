<?php
/** @var string $titulo */
/** @var array $headers */
/** @var array $rows */
?>

<h2 style="text-align:center;"><?= $titulo ?></h2>

<table border="1" cellpadding="5" cellspacing="0" width="100%">
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