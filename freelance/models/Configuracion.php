<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion".
 *
 * @property int $con_id Código identificador del registro
 * @property float $con_base_cotizacion_ss Valor base de cotización de seguridad social
 * @property float $con_retencion_imp_soc Porcentaje de retención a cuenta impuesto de sociedades
 */
class Configuracion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['con_retencion_imp_soc'], 'default', 'value' => 10],
            [['con_base_cotizacion_ss'], 'required'],
            [['con_base_cotizacion_ss', 'con_retencion_imp_soc'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'con_id' => 'Con ID',
            'con_base_cotizacion_ss' => 'Con Base Cotizacion Ss',
            'con_retencion_imp_soc' => 'Con Retencion Imp Soc',
        ];
    }

}
