<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "soc_cotizacion_seg_social".
 *
 * @property int $scss_id Código identificador del registro
 * @property int $soc_id Código del socio
 * @property string $scss_alta_abierta Campo para indicar si se trata de un alta abierta
 * @property string $scss_fecha_alta Fecha de alta a la seguridad social
 * @property string|null $scss_fecha_baja Fecha de baja a la seguridad social
 * @property int $scss_dias_cotizados Cantidad de días cotizados
 * @property int $scss_dias_liquidados Cantidad de días liquidados
 * @property int $scss_dias_pendientes Cantidad de días pendientes por liquidar
 * @property int $scss_coefcotizacion Coeficiente de cotización, por defecto 100%.
 *
 * @property CotizacionLiquidacion[] $cotizacionLiquidacions
 * @property RelSocCotizDetSegSocial[] $relSocCotizDetSegSocials
 * @property Socio $soc
 */
class SocCotizacionSegSocial extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const SCSS_ALTA_ABIERTA_SI = 'Si';
    const SCSS_ALTA_ABIERTA_NO = 'No';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soc_cotizacion_seg_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scss_fecha_baja'], 'default', 'value' => null],
            [['scss_dias_pendientes'], 'default', 'value' => 0],
            [['scss_coefcotizacion'], 'default', 'value' => 100],
            [['soc_id', 'scss_alta_abierta', 'scss_fecha_alta'], 'required'],
            [['soc_id', 'scss_dias_cotizados', 'scss_dias_liquidados', 'scss_dias_pendientes', 'scss_coefcotizacion'], 'integer'],
            [['scss_alta_abierta'], 'string'],
            [['scss_fecha_alta', 'scss_fecha_baja'], 'safe'],
            ['scss_alta_abierta', 'in', 'range' => array_keys(self::optsScssAltaAbierta())],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'scss_id' => 'Scss ID',
            'soc_id' => 'Soc ID',
            'scss_alta_abierta' => 'Scss Alta Abierta',
            'scss_fecha_alta' => 'Scss Fecha Alta',
            'scss_fecha_baja' => 'Scss Fecha Baja',
            'scss_dias_cotizados' => 'Scss Dias Cotizados',
            'scss_dias_liquidados' => 'Scss Dias Liquidados',
            'scss_dias_pendientes' => 'Scss Dias Pendientes',
            'scss_coefcotizacion' => 'Scss Coefcotizacion',
        ];
    }

    /**
     * Gets query for [[CotizacionLiquidacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCotizacionLiquidacions()
    {
        return $this->hasMany(CotizacionLiquidacion::class, ['scss_id' => 'scss_id']);
    }

    /**
     * Gets query for [[RelSocCotizDetSegSocials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelSocCotizDetSegSocials()
    {
        return $this->hasMany(RelSocCotizDetSegSocial::class, ['scss_id' => 'scss_id']);
    }

    /**
     * Gets query for [[Soc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoc()
    {
        return $this->hasOne(Socio::class, ['soc_id' => 'soc_id']);
    }


    /**
     * column scss_alta_abierta ENUM value labels
     * @return string[]
     */
    public static function optsScssAltaAbierta()
    {
        return [
            self::SCSS_ALTA_ABIERTA_SI => 'Si',
            self::SCSS_ALTA_ABIERTA_NO => 'No',
        ];
    }

    /**
     * @return string
     */
    public function displayScssAltaAbierta()
    {
        return self::optsScssAltaAbierta()[$this->scss_alta_abierta];
    }

    /**
     * @return bool
     */
    public function isScssAltaAbiertaSi()
    {
        return $this->scss_alta_abierta === self::SCSS_ALTA_ABIERTA_SI;
    }

    public function setScssAltaAbiertaToSi()
    {
        $this->scss_alta_abierta = self::SCSS_ALTA_ABIERTA_SI;
    }

    /**
     * @return bool
     */
    public function isScssAltaAbiertaNo()
    {
        return $this->scss_alta_abierta === self::SCSS_ALTA_ABIERTA_NO;
    }

    public function setScssAltaAbiertaToNo()
    {
        $this->scss_alta_abierta = self::SCSS_ALTA_ABIERTA_NO;
    }
}
