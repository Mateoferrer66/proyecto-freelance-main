<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provincia".
 *
 * @property int $prv_id Código identificador del registro
 * @property int $pai_id País
 * @property string $prv_nombre Nombre de la provincia
 * @property int $prv_eliminada Campo para indicar si la provincia se encuentra eliminada: 1 - Si, 0 - No
 *
 * @property Cliente[] $clientes
 * @property DatosFactura[] $datosFacturas
 * @property DatosPresupuesto[] $datosPresupuestos
 * @property Pais $pai
 * @property Socio[] $socios
 */
class Provincia extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provincia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prv_eliminada'], 'default', 'value' => 0],
            [['pai_id', 'prv_nombre'], 'required'],
            [['pai_id', 'prv_eliminada'], 'integer'],
            [['prv_nombre'], 'string', 'max' => 255],
            [['pai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::class, 'targetAttribute' => ['pai_id' => 'pai_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prv_id' => 'Prv ID',
            'pai_id' => 'Pai ID',
            'prv_nombre' => 'Prv Nombre',
            'prv_eliminada' => 'Prv Eliminada',
        ];
    }

    /**
     * Gets query for [[Clientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::class, ['prv_id' => 'prv_id']);
    }

    /**
     * Gets query for [[DatosFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatosFacturas()
    {
        return $this->hasMany(DatosFactura::class, ['prv_id' => 'prv_id']);
    }

    /**
     * Gets query for [[DatosPresupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatosPresupuestos()
    {
        return $this->hasMany(DatosPresupuesto::class, ['prv_id' => 'prv_id']);
    }

    /**
     * Gets query for [[Pai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPai()
    {
        return $this->hasOne(Pais::class, ['pai_id' => 'pai_id']);
    }

    /**
     * Gets query for [[Socios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocios()
    {
        return $this->hasMany(Socio::class, ['prv_id' => 'prv_id']);
    }

}
