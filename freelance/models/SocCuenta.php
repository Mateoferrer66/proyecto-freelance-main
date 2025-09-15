<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "soc_cuenta".
 *
 * @property int $scu_id Código identificador del registro
 * @property int $soc_id Código del socio al cual pertenece la cuenta
 * @property string $scu_cuenta Número de la cuenta (7 dígitos): subcuenta (3 dígitos) + código del socio (4 dígitos)
 * @property string $scu_descripcion Descripción de la cuenta
 *
 * @property Socio $soc
 */
class SocCuenta extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soc_cuenta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['soc_id', 'scu_cuenta', 'scu_descripcion'], 'required'],
            [['soc_id'], 'integer'],
            [['scu_cuenta'], 'string', 'max' => 7],
            [['scu_descripcion'], 'string', 'max' => 255],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'scu_id' => 'Scu ID',
            'soc_id' => 'Soc ID',
            'scu_cuenta' => 'Scu Cuenta',
            'scu_descripcion' => 'Scu Descripcion',
        ];
    }

    /**
     * Gets query for [[Soc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoc()
    {
        return $this->hasOne(Socio::class, ['soc_id' => 'soc_id']);
    }

}
