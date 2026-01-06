<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    // permitir acceso público a estas acciones
                        [
                            'actions' => ['login', 'error', 'captcha', 'contact', 'about', 'request-password-reset', 'reset-password', 'enter-reset-token'],
                            'allow' => true,
                            'roles' => ['?'], // para usuarios invitados
                        ],
                    // permitir a los usuarios autenticados acceder a todo lo demás
                    [
                        'allow' => true,
                        'roles' => ['@'], // para usuarios autenticados
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // 1. Facturas pendientes por aprobar
        $facturasPendientes = \app\models\Factura::find()
            ->where(['fac_aprobada' => 0])
            ->orWhere(['fac_aprobada' => null])
            ->all();
        $countFacturasPendientes = count($facturasPendientes);

        // 2. Presupuestos pendientes por aprobar
        $presupuestosPendientes = \app\models\Presupuesto::find()
            ->where(['pre_estado' => \app\models\Presupuesto::PRE_ESTADO_PENDIENTE])
            ->all();
        $countPresupuestosPendientes = count($presupuestosPendientes);

        // 3. Importes facturados por socio (Graph 2)
        $facturadoPorSocio = \app\models\Factura::find()
            ->alias('f')
            ->select(['s.soc_nombre', 's.soc_apellido', 'SUM(f.fac_total) as total_facturado'])
            ->joinWith('soc s')
            ->groupBy('f.soc_id')
            ->asArray()
            ->all();
        
        $chartLabels = [];
        $chartData = [];
        $backgroundColors = [];
        foreach ($facturadoPorSocio as $item) {
            $nombreCompleto = $item['soc_nombre'];
            if(isset($item['soc_apellido'])) $nombreCompleto .= ' ' . $item['soc_apellido'];
            $chartLabels[] = $nombreCompleto;
            $chartData[] = (float)$item['total_facturado'];
            $backgroundColors[] = "rgba(" . rand(50, 200) . ", " . rand(50, 200) . ", " . rand(50, 255) . ", 0.7)";
        }

        // 4. Total Usuarios
        $countUsuarios = \app\models\Usuario::find()->count();

        // 5. Total Clientes
        $countClientes = \app\models\Cliente::find()->count();

        // 6. Importes facturados por Cliente (Graph 1 - Fixed Query)
        // Fetch raw sums grouped by client ID first to avoid JOIN/AS issues
        $facturadoPorClienteRaw = \app\models\Factura::find()
            ->select(['cli_id', 'SUM(fac_total) as total_facturado'])
            ->groupBy('cli_id')
            ->asArray()
            ->all();

        $clientChartLabels = [];
        $clientChartData = [];
        $clientChartColors = [];
        
        // Get all client names to map
        $clientIds = array_column($facturadoPorClienteRaw, 'cli_id');
        $clientesNames = [];
        if (!empty($clientIds)) {
            $clientes = \app\models\Cliente::find()
                ->select(['cli_id', 'cli_nombre'])
                ->where(['cli_id' => $clientIds])
                ->asArray()
                ->all();
            foreach ($clientes as $c) {
                $clientesNames[$c['cli_id']] = $c['cli_nombre'];
            }
        }

        foreach ($facturadoPorClienteRaw as $item) {
             $cliId = $item['cli_id'];
             $name = isset($clientesNames[$cliId]) ? $clientesNames[$cliId] : 'Cliente ' . $cliId;
             $total = (float)$item['total_facturado'];
             
             if ($total > 0) { // Only show positive amounts
                $clientChartLabels[] = $name;
                $clientChartData[] = $total;
                $clientChartColors[] = "rgba(" . rand(0, 255) . ", " . rand(100, 255) . ", " . rand(100, 200) . ", 0.8)";
             }
        }

        return $this->render('index', [
            'facturasPendientes' => $facturasPendientes,
            'countFacturasPendientes' => $countFacturasPendientes,
            'presupuestosPendientes' => $presupuestosPendientes,
            'countPresupuestosPendientes' => $countPresupuestosPendientes,
            
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'chartColors' => $backgroundColors,
            
            'countUsuarios' => $countUsuarios,
            'countClientes' => $countClientes,
            'clientChartLabels' => $clientChartLabels,
            'clientChartData' => $clientChartData,
            'clientChartColors' => $clientChartColors
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'login_layout';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['factura/index']);
        }

        $model->usu_password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the password reset request form and sends email with token.
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'login_layout';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            Yii::$app->session->setFlash('success', 'Revise su email para las instrucciones de reinicio.');
            return $this->goHome();
        }

        return $this->render('requestPasswordResetToken', ['model' => $model]);
    }

    /**
     * Muestra un formulario simple donde el usuario puede pegar el token recibido por email
     * (útil cuando el link se rompe por quoted-printable). No modifica la BD.
     */
    public function actionEnterResetToken()
    {
        $this->layout = 'login_layout';
        if (Yii::$app->request->isPost) {
            $tok = Yii::$app->request->post('token', '');
            $tok = trim($tok);
            // limpiar artefactos comunes
            $tok = preg_replace('/=\r?\n/', '', $tok);
            $tok = str_ireplace('=3D', '=', $tok);
            $tok = str_ireplace('%3D', '=', $tok);
            $tok = str_replace(' ', '', $tok);
            if (!empty($tok)) {
                return $this->redirect(['site/reset-password', 'token' => $tok]);
            }
        }

        return $this->render('enterResetToken');
    }

    /**
     * Resets password given a valid token.
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'login_layout';
        $user = $this->validateTokenAndGetUser($token);
        if (!$user) {
            Yii::warning('ResetPassword: token invalid or expired: ' . substr($token, 0, 20) . '...', __METHOD__);
            Yii::$app->session->setFlash('error', 'Token inválido o expirado.');
            return $this->goHome();
        }

        Yii::info('ResetPassword: token valid for userId=' . $user->usu_id, __METHOD__);

    $model = new ResetPasswordForm($user);
    Yii::info('ResetPassword: rendering resetPassword view', __METHOD__);
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Contraseña cambiada correctamente.');
            return $this->redirect(['site/login']);
        }

        return $this->render('resetPassword', ['model' => $model]);
    }

    private function validateTokenAndGetUser($token)
    {
        // tolerancias: limpiar artefactos de quoted-printable o url-encoding
        if (empty($token)) {
            // intentar buscar parámetros alternativos
            $token = Yii::$app->request->get('tok', Yii::$app->request->get('token', ''));
        }

        $token = trim($token);
        // quitar soft line breaks de quoted-printable
        $token = preg_replace('/=\r?\n/', '', $token);
        // reemplazar secuencias '=3D' que aparecen en quoted-printable por '='
        $token = str_ireplace('=3D', '=', $token);
        // url-decode (por si vino codificado)
        $token = urldecode($token);
        // también reemplazar %3D por =
        $token = str_ireplace('%3D', '=', $token);
        // quitar espacios accidentales
        $token = str_replace(' ', '', $token);
        // si por artefacto quedó un prefijo '3D' pegado al inicio (p.ej. '3DMX...'), eliminarlo
        if (preg_match('/^3D[A-Za-z0-9\-_]/', $token)) {
            $token = substr($token, 2);
        }

        if (empty($token)) {
            return null;
        }

        $decoded = base64_decode(strtr($token, '-_', '+/'));
        if ($decoded === false) {
            Yii::warning('validateToken: base64_decode failed for token fragment: ' . substr($token, 0, 30), __METHOD__);
            return null;
        }

        $parts = explode('|', $decoded);
        if (count($parts) !== 3) {
            Yii::warning('validateToken: decoded token has wrong parts count=' . count($parts), __METHOD__);
            return null;
        }

        list($userId, $expiry, $sig) = $parts;
        if (time() > (int)$expiry) {
            Yii::warning('validateToken: token expired for userId=' . $userId, __METHOD__);
            return null;
        }

        $secret = Yii::$app->request->cookieValidationKey;
        $data = $userId . '|' . $expiry;
        $expected = hash_hmac('sha256', $data, $secret);
        if (!hash_equals($expected, $sig)) {
            Yii::warning('validateToken: signature mismatch for userId=' . $userId, __METHOD__);
            return null;
        }

        $user = \app\models\Usuario::findOne(['usu_id' => $userId, 'usu_eliminado' => 0]);
        return $user;
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
