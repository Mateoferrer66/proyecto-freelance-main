<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iva".
 *
 * @property int $iva_id Código identificador del registro
 * @property string $iva_concepto Concepto de IVA
 * @property float $iva_porcentaje Porcentaje de IVA
 *
 * @property Cliente[] $clientes
 * @property ConceptoFacturacion[] $conceptoFacturacions
 */
class Iva extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'iva';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iva_concepto', 'iva_porcentaje'], 'required'],
            [['iva_porcentaje'], 'number'],
            [['iva_concepto'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iva_id' => 'Iva ID',
            'iva_concepto' => 'Iva Concepto',
            'iva_porcentaje' => 'Iva Porcentaje',
        ];
    }

    /**
     * Gets query for [[Clientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::class, ['iva_id' => 'iva_id']);
    }

    /**
     * Gets query for [[ConceptoFacturacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConceptoFacturacions()
    {
        return $this->hasMany(ConceptoFacturacion::class, ['iva_id' => 'iva_id']);
    }

}
