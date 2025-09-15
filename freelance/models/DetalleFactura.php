<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_factura".
 *
 * @property int $dtf_id Código identificador del registro
 * @property int $fac_id Código de la factura
 * @property int $cof_id Concepto de facturación
 * @property string $dtf_descripcion Descripción del concepto de facturación
 * @property float $dtf_cantidad Cantidad
 * @property float $dtf_precio Precio unitario
 * @property float $dtf_iva Porcentaje de iva aplicado
 * @property float $dtf_subtotal Valor subtotal del detalle de factura
 *
 * @property ConceptoFacturacion $cof
 * @property Factura $fac
 */
class DetalleFactura extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fac_id', 'cof_id', 'dtf_descripcion', 'dtf_cantidad', 'dtf_precio', 'dtf_iva', 'dtf_subtotal'], 'required'],
            [['fac_id', 'cof_id'], 'integer'],
            [['dtf_cantidad', 'dtf_precio', 'dtf_iva', 'dtf_subtotal'], 'number'],
            [['dtf_descripcion'], 'string', 'max' => 255],
            [['cof_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConceptoFacturacion::class, 'targetAttribute' => ['cof_id' => 'cof_id']],
            [['fac_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factura::class, 'targetAttribute' => ['fac_id' => 'fac_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dtf_id' => 'Dtf ID',
            'fac_id' => 'Fac ID',
            'cof_id' => 'Cof ID',
            'dtf_descripcion' => 'Dtf Descripcion',
            'dtf_cantidad' => 'Dtf Cantidad',
            'dtf_precio' => 'Dtf Precio',
            'dtf_iva' => 'Dtf Iva',
            'dtf_subtotal' => 'Dtf Subtotal',
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
     * Gets query for [[Fac]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFac()
    {
        return $this->hasOne(Factura::class, ['fac_id' => 'fac_id']);
    }

}
