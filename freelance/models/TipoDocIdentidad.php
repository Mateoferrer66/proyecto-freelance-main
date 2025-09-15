<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_doc_identidad".
 *
 * @property int $tdo_id Código identificador del registro
 * @property string $tdo_nombre Nombre del tipo de documento de identidad
 * @property int $tdo_eliminado Campo para indicar si el registro se encuentra eliminado: 0 - No, 1 - Si
 *
 * @property Cliente[] $clientes
 * @property DatosFactura[] $datosFacturas
 * @property DatosPresupuesto[] $datosPresupuestos
 * @property Empresa[] $empresas
 * @property Socio[] $socios
 */
class TipoDocIdentidad extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_doc_identidad';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tdo_eliminado'], 'default', 'value' => 0],
            [['tdo_nombre'], 'required'],
            [['tdo_eliminado'], 'integer'],
            [['tdo_nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tdo_id' => 'Tdo ID',
            'tdo_nombre' => 'Tdo Nombre',
            'tdo_eliminado' => 'Tdo Eliminado',
        ];
    }

    /**
     * Gets query for [[Clientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::class, ['tdo_id' => 'tdo_id']);
    }

    /**
     * Gets query for [[DatosFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatosFacturas()
    {
        return $this->hasMany(DatosFactura::class, ['tdo_id' => 'tdo_id']);
    }

    /**
     * Gets query for [[DatosPresupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatosPresupuestos()
    {
        return $this->hasMany(DatosPresupuesto::class, ['tdo_id' => 'tdo_id']);
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresas()
    {
        return $this->hasMany(Empresa::class, ['tdo_id' => 'tdo_id']);
    }

    /**
     * Gets query for [[Socios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocios()
    {
        return $this->hasMany(Socio::class, ['tdo_id' => 'tdo_id']);
    }

}
