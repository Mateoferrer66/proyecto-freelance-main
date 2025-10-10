<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;

/**
 * BaseController implementa el control de acceso por defecto para la aplicación.
 * Requiere que el usuario esté logueado para todas las acciones.
 * Los controladores que necesiten acciones públicas (como SiteController) deben
 * sobreescribir estas reglas de acceso.
 */
class BaseController extends Controller
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
                    // Permite el acceso a la acción de login y error para invitados
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['login', 'error', 'captcha'],
                        'roles' => ['?'],
                    ],
                    // Permite todas las acciones a los usuarios autenticados
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // Niega todo lo demás por defecto
                ],
            ],
        ];
    }
}
