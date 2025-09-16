<?php

namespace app\assets;

use yii\web\AssetBundle;

class PanelAsset extends AssetBundle
{
    public $basePath = '@webroot/assets-custom';
    public $baseUrl = '@web/assets-custom';

    public $css = [
        'css/bootstrap.min.css',
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
        // 🔹 Núcleo base: jQuery primero
        'js/jquery.min.js',

        // 🔹 Bootstrap depende de jQuery
        'js/bootstrap.bundle.min.js',

        // 🔹 Loader visual (puede ir antes o después de Bootstrap)
        'js/pace.min.js',

        // 🔹 Plugins que extienden el DOM (deben ir después de jQuery y Bootstrap)
        'plugins/simplebar/js/simplebar.min.js',
        'plugins/metismenu/js/metisMenu.min.js',
        'plugins/perfect-scrollbar/js/perfect-scrollbar.js',

        // 🔹 DataTables (requiere jQuery y Bootstrap)
        'plugins/datatable/js/jquery.dataTables.min.js',
        'plugins/datatable/js/dataTables.bootstrap5.min.js',

        // 🔹 DateTimePicker (requiere moment.js)
        'plugins/bootstrap-material-datetimepicker/js/moment.min.js',
        'plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js',

        // 🔹 Tu script personalizado (debe ir al final para que todo esté cargado)
        'js/app.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}