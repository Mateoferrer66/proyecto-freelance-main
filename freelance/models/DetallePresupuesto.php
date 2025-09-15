<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_presupuesto".
 *
 * @property int $dtp_id Código identificador del registro
 * @property int $pre_id Código del presupuesto
 * @property int $cof_id Concepto de facturación
 * @property string $dtp_descripcion Descripción del concepto de facturación
 * @property float $dtp_cantidad Cantidad
 * @property float $dtp_precio Precio unitario
 * @property float $dtp_iva Porcentaje de iva aplicado
 * @property float $dtp_subtotal Valor subtotal del detalle de presupuesto
 *
 * @property ConceptoFacturacion $cof
 * @property Presupuesto $pre
 */
class DetallePresupuesto extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_presupuesto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pre_id', 'cof_id', 'dtp_descripcion', 'dtp_cantidad', 'dtp_precio', 'dtp_iva', 'dtp_subtotal'], 'required'],
            [['pre_id', 'cof_id'], 'integer'],
            [['dtp_cantidad', 'dtp_precio', 'dtp_iva', 'dtp_subtotal'], 'number'],
            [['dtp_descripcion'], 'string', 'max' => 255],
            [['cof_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoFacturacion::class, 'targetAttribute' => ['cof_id' => 'cof_id']],
            [['pre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Presupuesto::class, 'targetAttribute' => ['pre_id' => 'pre_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dtp_id' => 'Dtp ID',
            'pre_id' => 'Pre ID',
            'cof_id' => 'Cof ID',
            'dtp_descripcion' => 'Dtp Descripcion',
            'dtp_cantidad' => 'Dtp Cantidad',
            'dtp_precio' => 'Dtp Precio',
            'dtp_iva' => 'Dtp Iva',
            'dtp_subtotal' => 'Dtp Subtotal',
        ];
    }

    /**
     * Gets query for [[Cof]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCof()
    {
        return $this->hasOne(ConceptoFacturacion::class, ['cof_id' => 'cof_id']);
    }

    /**
     * Gets query for [[Pre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPre()
    {
        return $this->hasOne(Presupuesto::class, ['pre_id' => 'pre_id']);
    }

}
