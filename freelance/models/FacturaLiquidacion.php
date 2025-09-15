<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factura_liquidacion".
 *
 * @property int $fli_id Código identificador del registro
 * @property int $liq_id Código de la liquidación
 * @property int $fac_id Código de la factura
 * @property string $fli_parcial Campo para indicar si se va a realizar una liquidación parcial de la factura
 * @property float|null $fli_valor_general Campo para almacenar el valor a liquidar, en caso de que sea una liquidación parcial
 * @property float|null $fli_valor_suplidos
 *
 * @property Factura $fac
 * @property Liquidacion $liq
 */
class FacturaLiquidacion extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const FLI_PARCIAL_SI = 'si';
    const FLI_PARCIAL_NO = 'no';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factura_liquidacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fli_valor_suplidos'], 'default', 'value' => null],
            [['fli_valor_general'], 'default', 'value' => 0],
            [['liq_id', 'fac_id', 'fli_parcial'], 'required'],
            [['liq_id', 'fac_id'], 'integer'],
            [['fli_parcial'], 'string'],
            [['fli_valor_general', 'fli_valor_suplidos'], 'number'],
            ['fli_parcial', 'in', 'range' => array_keys(self::optsFliParcial())],
            [['fac_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factura::class, 'targetAttribute' => ['fac_id' => 'fac_id']],
            [['liq_id'], 'exist', 'skipOnError' => true, 'targetClass' => Liquidacion::class, 'targetAttribute' => ['liq_id' => 'liq_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fli_id' => 'Fli ID',
            'liq_id' => 'Liq ID',
            'fac_id' => 'Fac ID',
            'fli_parcial' => 'Fli Parcial',
            'fli_valor_general' => 'Fli Valor General',
            'fli_valor_suplidos' => 'Fli Valor Suplidos',
        ];
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

    /**
     * Gets query for [[Liq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiq()
    {
        return $this->hasOne(Liquidacion::class, ['liq_id' => 'liq_id']);
    }


    /**
     * column fli_parcial ENUM value labels
     * @return string[]
     */
    public static function optsFliParcial()
    {
        return [
            self::FLI_PARCIAL_SI => 'si',
            self::FLI_PARCIAL_NO => 'no',
        ];
    }

    /**
     * @return string
     */
    public function displayFliParcial()
    {
        return self::optsFliParcial()[$this->fli_parcial];
    }

    /**
     * @return bool
     */
    public function isFliParcialSi()
    {
        return $this->fli_parcial === self::FLI_PARCIAL_SI;
    }

    public function setFliParcialToSi()
    {
        $this->fli_parcial = self::FLI_PARCIAL_SI;
    }

    /**
     * @return bool
     */
    public function isFliParcialNo()
    {
        return $this->fli_parcial === self::FLI_PARCIAL_NO;
    }

    public function setFliParcialToNo()
    {
        $this->fli_parcial = self::FLI_PARCIAL_NO;
    }
}
