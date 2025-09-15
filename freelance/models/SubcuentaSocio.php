<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subcuenta_socio".
 *
 * @property int $scs_id Código identificador del registro
 * @property string $scs_numero Número de la subcuenta (3 dígitos)
 * @property string $scs_descripcion Descripción de la subcuenta, si es vacío entonces corresponde al nombre del socio
 */
class SubcuentaSocio extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subcuenta_socio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scs_numero', 'scs_descripcion'], 'required'],
            [['scs_numero'], 'string', 'max' => 3],
            [['scs_descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'scs_id' => 'Scs ID',
            'scs_numero' => 'Scs Numero',
            'scs_descripcion' => 'Scs Descripcion',
        ];
    }

}
