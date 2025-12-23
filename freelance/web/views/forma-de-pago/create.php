<?php
// En create.php
use yii\helpers\Html;
?>
<div class="forma-de-pago-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>