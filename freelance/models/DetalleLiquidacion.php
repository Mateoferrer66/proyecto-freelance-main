<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_liquidacion".
 *
 * @property int $dtl_id Código identificador del registro
 * @property int $liq_id Código de la liquidación
 * @property int $col_id Código del concepto de liquidación
 * @property float $dtl_importe Importe del concepto de liquidación
 * @property float|null $dtl_porcentaje Porcentaje aplicado, en caso de que el concepto sea tipo porcentaje
 *
 * @property ConceptoLiquidacion $col
 * @property Liquidacion $liq
 */
class DetalleLiquidacion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_liquidacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dtl_porcentaje'], 'default', 'value' => null],
            [['liq_id', 'col_id', 'dtl_importe'], 'required'],
            [['liq_id', 'col_id'], 'integer'],
            [['dtl_importe', 'dtl_porcentaje'], 'number'],
            [['col_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoLiquidacion::class, 'targetAttribute' => ['col_id' => 'col_id']],
            [['liq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Liquidacion::class, 'targetAttribute' => ['liq_id' => 'liq_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dtl_id' => 'Dtl ID',
            'liq_id' => 'Liq ID',
            'col_id' => 'Col ID',
            'dtl_importe' => 'Dtl Importe',
            'dtl_porcentaje' => 'Dtl Porcentaje',
        ];
    }

    /**
     * Gets query for [[Col]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCol()
    {
        return $this->hasOne(ConceptoLiquidacion::class, ['col_id' => 'col_id']);
    }

    /**
     * Gets query for [[Liq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiq()
    {
        return $this->hasOne(Liquidacion::class, ['liq_id' => 'liq_id']);
    }

}
