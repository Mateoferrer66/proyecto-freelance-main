<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Cambiar Contraseña';
?>

<div class="site-change-password">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success">
                            <?= Yii::$app->session->getFlash('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        
                        <?php if (!empty(Yii::$app->user->identity->soc_password)): ?>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle"></i> Aún no tiene una contraseña configurada. Establezca una nueva contraseña.
                        </div>
                        <input type="hidden" name="current_password" value="">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                            <div class="form-text">Mínimo 8 caracteres</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <?= Html::submitButton('Guardar Contraseña', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Cancelar', ['site/index'], ['class' => 'btn btn-secondary']) ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
