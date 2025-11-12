<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "banco".
 *
 * @property int $ban_id Código identificador del registro
 * @property string $ban_nombre Nombre del banco
 * @property string $ban_numcuenta Número de cuenta
 * @property string $ban_titular_tarjeta Nombre del titular de la tarjeta
 * @property int $ban_eliminado Campo para indicar si el registro está eliminado: 0 - No, 1 - Si
 *
 * @property CuentasFactura[] $cuentasFacturas
 * @property CuentasPresupuesto[] $cuentasPresupuestos
 * @property Factura[] $facs
 * @property Presupuesto[] $pres
 */
class Banco extends \yii\db\ActiveRecord
{
    public $ban_titular_tarjeta;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ban_eliminado'], 'default', 'value' => 0],
            [['ban_nombre', 'ban_numcuenta', 'ban_titular_tarjeta'], 'required'],
            [['ban_eliminado'], 'integer'],
            [['ban_nombre', 'ban_titular_tarjeta'], 'string', 'max' => 255],
            [['ban_numcuenta'], 'string', 'max' => 24],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ban_id' => 'ID',
            'ban_nombre' => 'Nombre',
            'ban_numcuenta' => 'Numcuenta',
            'ban_titular_tarjeta' => 'Nombre Titular',
            'ban_eliminado' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[CuentasFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuentasFacturas()
    {
        return $this->hasMany(CuentasFactura::class, ['ban_id' => 'ban_id']);
    }

    /**
     * Gets query for [[CuentasPresupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuentasPresupuestos()
    {
        return $this->hasMany(CuentasPresupuesto::class, ['ban_id' => 'ban_id']);
    }

    /**
     * Gets query for [[Facs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacs()
    {
        return $this->hasMany(Factura::class, ['fac_id' => 'fac_id'])->viaTable('cuentas_factura', ['ban_id' => 'ban_id']);
    }

    /**
     * Gets query for [[Pres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPres()
    {
        return $this->hasMany(Presupuesto::class, ['pre_id' => 'pre_id'])->viaTable('cuentas_presupuesto', ['ban_id' => 'ban_id']);
    }

}
