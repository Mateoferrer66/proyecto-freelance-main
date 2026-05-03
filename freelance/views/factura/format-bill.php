<?php
/** @var app\models\Factura $model */
/** @var app\models\DatosFactura $modelBillDataE */
/** @var app\models\DatosFactura $modelBillDataR */
/** @var app\models\DetalleFactura[] $modelsBillDetail */
/** @var app\models\CuentasFactura[] $modelsAccountBill */
/** @var float $baseIva */
/** @var array $porcIva */
/** @var float $cuota */
/** @var string $logoPath */
use app\components\UtilitiesHelper;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; }
        .header-bar { background-color: #f6891e; color: #000; font-weight: bold; }
    </style>
</head>
<body>

<!-- CABECERA -->
<table width="100%" border="0">
    <tr>
        <td width="400">
            <?php if (!empty($logoPath) && file_exists($logoPath)): ?>
                <img src="<?= $logoPath ?>" width="200" height="58"/>
            <?php else: ?>
                <img src="<?= Yii::getAlias('@webroot') ?>/assets-custom/images/logo-freelance3.jpg" width="200" height="58"/>
            <?php endif; ?>
        </td>
        <td>
            <strong>FACTURA: </strong><?= $model->fac_numero ?><br>
            <strong>Fecha: </strong><?= UtilitiesHelper::db2dateHour($model->fac_fecha, false) ?>
        </td>
    </tr>
</table>

<br>

<!-- EMISOR Y RECEPTOR -->
<table width="100%" border="0">
    <tr>
        <td width="400" valign="top">
            <strong>Socio - <?= $model->soc->soc_nombre . ' ' . $model->soc->soc_apellido . ' - ' . $model->soc->soc_numero ?></strong><br><br>
            <strong style="font-size:14px;"><?= $modelBillDataE->daf_nombre ?></strong><br>
            <?= $modelBillDataE->daf_numdocide ?><br>
            <?= $modelBillDataE->daf_direccion ?><br>
            <?= $modelBillDataE->daf_cod_postal . ', ' . $modelBillDataE->daf_poblacion ?>
        </td>
        <td width="350" valign="top" align="right">
            <strong>CLIENTE</strong><br><br>
            <?php
                $clientName = $modelBillDataR->daf_nombre;
                if (strlen($clientName) > 35) {
                    $clientName = wordwrap($clientName, 40, '<br>', true);
                }
            ?>
            <?= $clientName ?> - <?= $model->cli->cli_numero ?><br>
            <?php if (isset($model->cli->tdo_id) && $model->cli->tdo_id == 4): ?>
                <?= $model->cli->cli_docinipais ?>
            <?php endif; ?>
            <?= $modelBillDataR->daf_numdocide ?><br>
            <?= $modelBillDataR->daf_direccion ?><br>
            <?= $modelBillDataR->daf_cod_postal ?>
            <?php if ($modelBillDataR->prv): ?>
                , <?= $modelBillDataR->prv->prv_nombre ?>
            <?php endif; ?>
            <?php if ($modelBillDataR->pai): ?>
                <br><?= $modelBillDataR->pai->pai_nombre ?>
            <?php endif; ?>
        </td>
    </tr>
</table>

<br>

<!-- CABECERA DETALLE -->
<table width="100%" border="0">
    <tr class="header-bar" bgcolor="#f6891e">
        <td width="320" align="center"><strong>Descripción</strong></td>
        <td width="110" align="center"><strong>Cantidad</strong></td>
        <td width="110" align="center"><strong>Precio</strong></td>
        <td width="160" align="center"><strong>Importe</strong></td>
    </tr>
    <?php foreach ($modelsBillDetail as $det): ?>
    <tr>
        <td width="320"><?= $det->dtf_descripcion ?></td>
        <?php if ($det->cof->cof_codigo != '03'): ?>
            <td width="110" align="center"><?= $det->dtf_cantidad ?></td>
            <td width="110" align="right"><?= UtilitiesHelper::formatEuroPDF($det->dtf_precio) ?></td>
            <td width="160" align="right"><?= UtilitiesHelper::formatEuroPDF($det->dtf_cantidad * $det->dtf_precio) ?></td>
        <?php else: ?>
            <td width="110">&nbsp;</td>
            <td width="110">&nbsp;</td>
            <td width="160">&nbsp;</td>
        <?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>

<br>

<!-- OBSERVACIONES -->
<?php if (trim($model->fac_observaciones) != ''): ?>
<table width="100%" border="0">
    <tr>
        <td><strong>Observaciones:</strong></td>
    </tr>
    <tr>
        <td><?= nl2br($model->fac_observaciones) ?></td>
    </tr>
</table>
<br>
<?php endif; ?>

<!-- TOTALES IVA -->
<table width="100%" border="0">
    <tr>
        <td colspan="4"><strong>Por favor al realizar el pago indicar el número de la factura</strong></td>
    </tr>
    <tr bgcolor="#f6891e">
        <td width="164" align="center"><strong>Base de IVA</strong></td>
        <td width="164" align="center"><strong>%IVA</strong></td>
        <td width="164" align="center"><strong>Cuota</strong></td>
        <td width="164" align="center"><strong>Totales</strong></td>
    </tr>
    <tr>
        <td align="center"><?= UtilitiesHelper::formatEuroPDF($baseIva) ?></td>
        <td align="center">
            <?php foreach ($porcIva as $porc): ?>
                <?= UtilitiesHelper::formatEuroPDF($porc) ?><br>
            <?php endforeach; ?>
        </td>
        <td align="center"><?= UtilitiesHelper::formatEuroPDF($cuota) ?></td>
        <td align="center">
            <table width="100%" border="0">
                <tr>
                    <td align="left"><strong>Neto</strong></td>
                    <td align="right"><?= UtilitiesHelper::formatEuroPDF($model->fac_subtotal - $model->fac_gastos_suplidos) ?> <?= $model->fac_money ?></td>
                </tr>
                <?php if (!empty($model->fac_gastos_suplidos) && $model->fac_gastos_suplidos != 0): ?>
                <tr>
                    <td align="left"><strong>Gastos</strong></td>
                    <td align="right"><?= UtilitiesHelper::formatEuroPDF($model->fac_gastos_suplidos) ?> <?= $model->fac_money ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td align="left"><strong>IVA</strong></td>
                    <td align="right"><?= UtilitiesHelper::formatEuroPDF($cuota) ?> <?= $model->fac_money ?></td>
                </tr>
                <tr>
                    <td align="left" style="font-size:14px;"><strong>TOTAL</strong></td>
                    <td align="right" style="font-size:14px;"><strong><?= UtilitiesHelper::formatEuroPDF($model->fac_total) ?> <?= $model->fac_money ?></strong></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<!-- FORMA DE PAGO -->
<table width="100%" border="0">
    <tr bgcolor="#f6891e">
        <td><strong>&nbsp;&nbsp;Forma de pago</strong></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><strong><?= $model->fdp->fdp_nombre ?></strong></td>
    </tr>

    <?php if ($model->fac_money === 'Euros'): ?>
        <?php foreach ($modelsAccountBill as $cf): ?>
            <?php
                $banco = $cf->ban;
                $strAcc = str_replace(' ', '', $banco->ban_numcuenta);
                $accountNumber = implode(' ', str_split($strAcc, 4));
            ?>
            <tr>
                <td><?= $banco->ban_nombre ?>: <?= $accountNumber ?></td>
            </tr>
        <?php endforeach; ?>

    <?php elseif ($model->fac_money === '£'): ?>
        <tr>
            <td>
                <table border="0">
                    <tr>
                        <td><b>Account holder</b></td>
                        <td><b>Sort code</b></td>
                        <td><b>Account number</b></td>
                        <td><b>IBAN</b></td>
                        <td><b>Address</b></td>
                    </tr>
                    <tr>
                        <td>FREELANCE SCM</td>
                        <td>23-14-70</td>
                        <td>44576548</td>
                        <td>GB81 TRWI 2314 7044 5765 48</td>
                        <td>TransferWise<br>56 Shoreditch High Street<br>London, E1 6JJ, United Kingdom</td>
                    </tr>
                </table>
            </td>
        </tr>

    <?php elseif ($model->fac_money === 'US$'): ?>
        <tr>
            <td>
                <table border="0">
                    <tr>
                        <td><b>Account holder</b></td>
                        <td><b>Routing number</b></td>
                        <td><b>Account number</b></td>
                        <td><b>Account type</b></td>
                        <td><b>Address</b></td>
                    </tr>
                    <tr>
                        <td>FREELANCE SCM</td>
                        <td>084009519</td>
                        <td>9600 0010 8904 2323</td>
                        <td>Checking</td>
                        <td>TransferWise<br>19 W 24th Street<br>New York NY 10010, United States</td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endif; ?>
</table>

<!-- PIE -->
<table width="100%">
    <tr>
        <td align="center" style="font-size:9px;">
            <strong>Registro de Cooperativas de la Comunidad de Madrid, Tomo 34, folio 4269, n&#176; inscripción 28/CM4269</strong>
        </td>
    </tr>
</table>

</body>
</html>