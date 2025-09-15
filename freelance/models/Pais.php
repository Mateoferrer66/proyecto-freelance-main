<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pais".
 *
 * @property int $pai_id Código identificador del registro
 * @property string $pai_nombre Nombre del país
 * @property string $pai_iniciales Iniciales del país
 * @property int $pai_eliminado Campo para indicar si el pais se encuentra eliminado: 1 - Si, 0 - No
 *
 * @property Cliente[] $clientes
 * @property DatosFactura[] $datosFacturas
 * @property Provincia[] $provincias
 */
class Pais extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pais';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pai_eliminado'], 'default', 'value' => 0],
            [['pai_nombre', 'pai_iniciales'], 'required'],
            [['pai_eliminado'], 'integer'],
            [['pai_nombre'], 'string', 'max' => 255],
            [['pai_iniciales'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pai_id' => 'Pai ID',
            'pai_nombre' => 'Pai Nombre',
            'pai_iniciales' => 'Pai Iniciales',
            'pai_eliminado' => 'Pai Eliminado',
        ];
    }

    /**
     * Gets query for [[Clientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::class, ['pai_id' => 'pai_id']);
    }

    /**
     * Gets query for [[DatosFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatosFacturas()
    {
        return $this->hasMany(DatosFactura::class, ['pai_id' => 'pai_id']);
    }

    /**
     * Gets query for [[Provincias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvincias()
    {
        return $this->hasMany(Provincia::class, ['pai_id' => 'pai_id']);
    }

}
