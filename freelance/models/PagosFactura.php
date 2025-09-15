<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pagos_factura".
 *
 * @property int $paf_code Código identificador del registro
 * @property int $fac_id Código de la factura
 * @property string $paf_fecha
 * @property string $paf_tipo
 * @property float $paf_valor
 *
 * @property Factura $fac
 */
class PagosFactura extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const PAF_TIPO_GENERAL = 'General';
    const PAF_TIPO_SUPLIDOS = 'Suplidos';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagos_factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paf_tipo'], 'default', 'value' => 'General'],
            [['fac_id', 'paf_fecha', 'paf_valor'], 'required'],
            [['fac_id'], 'integer'],
            [['paf_fecha'], 'safe'],
            [['paf_tipo'], 'string'],
            [['paf_valor'], 'number'],
            ['paf_tipo', 'in', 'range' => array_keys(self::optsPafTipo())],
            [['fac_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factura::class, 'targetAttribute' => ['fac_id' => 'fac_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paf_code' => 'Paf Code',
            'fac_id' => 'Fac ID',
            'paf_fecha' => 'Paf Fecha',
            'paf_tipo' => 'Paf Tipo',
            'paf_valor' => 'Paf Valor',
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
     * column paf_tipo ENUM value labels
     * @return string[]
     */
    public static function optsPafTipo()
    {
        return [
            self::PAF_TIPO_GENERAL => 'General',
            self::PAF_TIPO_SUPLIDOS => 'Suplidos',
        ];
    }

    /**
     * @return string
     */
    public function displayPafTipo()
    {
        return self::optsPafTipo()[$this->paf_tipo];
    }

    /**
     * @return bool
     */
    public function isPafTipoGeneral()
    {
        return $this->paf_tipo === self::PAF_TIPO_GENERAL;
    }

    public function setPafTipoToGeneral()
    {
        $this->paf_tipo = self::PAF_TIPO_GENERAL;
    }

    /**
     * @return bool
     */
    public function isPafTipoSuplidos()
    {
        return $this->paf_tipo === self::PAF_TIPO_SUPLIDOS;
    }

    public function setPafTipoToSuplidos()
    {
        $this->paf_tipo = self::PAF_TIPO_SUPLIDOS;
    }
}
