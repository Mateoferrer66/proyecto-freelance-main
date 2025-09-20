<?php

namespace app\assets;

use yii\web\AssetBundle;

class PanelAsset extends AssetBundle
{
    public $basePath = '@webroot/assets-custom';
    public $baseUrl = '@web/assets-custom';

    public $css = [
        // 'css/bootstrap.min.css', // Eliminado para usar la versión de Yii2
        'css/bootstrap-extended.css',
        'css/app.css',
        'css/icons.css',
        'css/pace.min.css',
        'plugins/simplebar/css/simplebar.css',
        'plugins/datetimepicker/css/classic.css',
        'plugins/datetimepicker/css/classic.time.css',
        'plugins/datetimepicker/css/classic.date.css',
        'plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css',
        'plugins/perfect-scrollbar/css/perfect-scrollbar.css',
        'plugins/metismenu/css/metisMenu.min.css',
        'plugins/datatable/css/dataTables.bootstrap5.min.css',
    ];

    public $js = [
        // 'js/jquery.min.js', // Eliminado, YiiAsset se encarga de esto
        // 'js/bootstrap.bundle.min.js', // Eliminado, BootstrapAsset se encarga de esto

        'js/pace.min.js',
        'plugins/simplebar/js/simplebar.min.js',
        'plugins/metismenu/js/metisMenu.min.js',
        'plugins/perfect-scrollbar/js/perfect-scrollbar.js',
        'plugins/datatable/js/jquery.dataTables.min.js',
        'plugins/datatable/js/dataTables.bootstrap5.min.js',
        'plugins/bootstrap-material-datetimepicker/js/moment.min.js',
        'plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js',
        'js/app.js',
    ];

    public $depends = [
        'yii\web\YiiAsset', // Asegura que yii.js y jQuery se carguen antes
        'yii\bootstrap5\BootstrapAsset', // Asegura que Bootstrap 5 se cargue antes
    ];
}