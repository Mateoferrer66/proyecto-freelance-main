<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cotizacion_presupuesto_liquidacion".
 *
 * @property int $ctl_id Código identificador del registro
 * @property int $liq_id Código de la liquidación
 * @property int $scss_id Código del registro de cotización a seguridad social del socio
 * @property string $ctl_fecha_alta Fecha de alta
 * @property string $ctl_fecha_baja Fecha de baja
 * @property int $ctl_dias Cantidad de días liquidados
 */
class CotizacionPresupuestoLiquidacion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cotizacion_presupuesto_liquidacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['liq_id', 'scss_id', 'ctl_fecha_alta', 'ctl_fecha_baja', 'ctl_dias'], 'required'],
            [['liq_id', 'scss_id', 'ctl_dias'], 'integer'],
            [['ctl_fecha_alta', 'ctl_fecha_baja'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ctl_id' => 'Ctl ID',
            'liq_id' => 'Liq ID',
            'scss_id' => 'Scss ID',
            'ctl_fecha_alta' => 'Ctl Fecha Alta',
            'ctl_fecha_baja' => 'Ctl Fecha Baja',
            'ctl_dias' => 'Ctl Dias',
        ];
    }

}
