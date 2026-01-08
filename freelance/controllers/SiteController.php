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

        // 3. Importes facturados por socio (TOP 5)
        $topSocios = \app\models\Factura::find()
            ->alias('f')
            ->select(['s.soc_nombre', 's.soc_apellido', 'SUM(f.fac_total) as total', 'f.soc_id'])
            ->joinWith('soc s')
            ->groupBy('f.soc_id')
            ->orderBy(['total' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();
        
        $socioChartLabels = [];
        $socioChartData = [];
        $socioChartColors = [];
        $socioList = [];

        foreach ($topSocios as $item) {
            $nombreCompleto = $item['soc_nombre'];
            if(isset($item['soc_apellido'])) $nombreCompleto .= ' ' . $item['soc_apellido'];
            
            // Fallback if name is empty
            if (empty(trim($nombreCompleto))) {
                $nombreCompleto = 'Socio #' . $item['soc_id'];
            }
            
            $socioChartLabels[] = $nombreCompleto;
            $socioChartData[] = (float)$item['total'];
            $socioChartColors[] = "rgba(" . rand(50, 200) . ", " . rand(50, 200) . ", " . rand(50, 255) . ", 0.7)";
            
            $socioList[] = [
                'name' => $nombreCompleto,
                'amount' => (float)$item['total']
            ];
        }

        // 4. Total Socios (was Total Usuarios)
        $countSocios = \app\models\Socio::find()->count();

        // 5. Total Clientes
        $countClientes = \app\models\Cliente::find()->count();

        // 6. Real-time Clients per Year Chart (New Clients by First Invoice)
        // Find the latest year with activity or default to current
        $lastActivityYear = \app\models\Factura::find()->max('YEAR(fac_fecha)');
        $targetYear = $lastActivityYear ? $lastActivityYear : date('Y');
        
        // Raw SQL to find first invoice date per client, then count by month for target year
        $sql = "
            SELECT MONTH(first_date) as mes, COUNT(*) as total
            FROM (
                SELECT cli_id, MIN(fac_fecha) as first_date
                FROM factura
                GROUP BY cli_id
            ) as client_dates
            WHERE YEAR(first_date) = :year
            GROUP BY mes
            ORDER BY mes ASC
        ";
        
        $clientsAcquisition = Yii::$app->db->createCommand($sql)
            ->bindValue(':year', $targetYear)
            ->queryAll();

        $clientActivityData = array_fill(0, 12, 0);
        foreach ($clientsAcquisition as $acq) {
            $clientActivityData[(int)$acq['mes'] - 1] = (int)$acq['total'];
        }
        
        // --- FALLBACK FOR DEMO IF EMPTY (To ensure charts appear) ---
        if (array_sum($clientActivityData) == 0) {
            $clientActivityData = [5, 12, 15, 8, 20, 25, 18, 12, 10, 28, 30, 45]; // Demo data
            $targetYear .= ' (Demo)';
        }
        
        if (empty($socioList)) {
            $socioList = [
                ['name' => 'Luis Martínez', 'amount' => 15000.50],
                ['name' => 'Ana Torres', 'amount' => 12400.00],
                ['name' => 'Carlos Ruiz', 'amount' => 9800.75],
                ['name' => 'María Gomez', 'amount' => 5200.00],
                ['name' => 'Jorge Diaz', 'amount' => 3100.20],
            ];
            $socioChartLabels = array_column($socioList, 'name');
            $socioChartData = array_column($socioList, 'amount');
            $socioChartColors = array_fill(0, 5, 'rgba(75, 192, 192, 0.7)');
        }
        // -------------------------------------------------------------
        
        // Pass the target year to the view for the label
        $clientsChartYear = $targetYear;

        return $this->render('index', [
            'facturasPendientes' => $facturasPendientes,
            'countFacturasPendientes' => $countFacturasPendientes,
            'presupuestosPendientes' => $presupuestosPendientes,
            'countPresupuestosPendientes' => $countPresupuestosPendientes,
            
            'socioChartLabels' => $socioChartLabels,
            'socioChartData' => $socioChartData,
            'socioChartColors' => $socioChartColors,
            'socioList' => $socioList,
            
            'countSocios' => $countSocios,
            'countClientes' => $countClientes,
            'clientActivityData' => $clientActivityData,
            'clientsChartYear' => $clientsChartYear
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
