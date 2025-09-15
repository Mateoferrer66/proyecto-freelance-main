<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "forma_de_pago".
 *
 * @property int $fdp_id Código identificador del registro
 * @property string $fdp_nombre Nombre de la forma de pago
 * @property int $fdp_eliminada Campo para indicar si el registro se encuentra eliminado: 0 - No, 1 - Si
 *
 * @property Cliente[] $clientes
 * @property Factura[] $facturas
 * @property Presupuesto[] $presupuestos
 */
class FormaDePago extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forma_de_pago';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fdp_eliminada'], 'default', 'value' => 0],
            [['fdp_nombre'], 'required'],
            [['fdp_eliminada'], 'integer'],
            [['fdp_nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fdp_id' => 'Fdp ID',
            'fdp_nombre' => 'Fdp Nombre',
            'fdp_eliminada' => 'Fdp Eliminada',
        ];
    }

    /**
     * Gets query for [[Clientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::class, ['fdp_id' => 'fdp_id']);
    }

    /**
     * Gets query for [[Facturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacturas()
    {
        return $this->hasMany(Factura::class, ['fdp_id' => 'fdp_id']);
    }

    /**
     * Gets query for [[Presupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPresupuestos()
    {
        return $this->hasMany(Presupuesto::class, ['fdp_id' => 'fdp_id']);
    }

}
