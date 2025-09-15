<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rel_soc_cotiz_det_seg_social".
 *
 * @property int $dtdss_id Código del detalle de documento de alta/baja seguridad social
 * @property int $scss_id Código del registro de días cotizados por el socio
 *
 * @property DetalleDocSegSocial $dtdss
 * @property SocCotizacionSegSocial $scss
 */
class RelSocCotizDetSegSocial extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rel_soc_cotiz_det_seg_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dtdss_id', 'scss_id'], 'required'],
            [['dtdss_id', 'scss_id'], 'integer'],
            [['dtdss_id'], 'unique'],
            [['dtdss_id'], 'exist', 'skipOnError' => true, 'targetClass' => DetalleDocSegSocial::class, 'targetAttribute' => ['dtdss_id' => 'dtdss_id']],
            [['scss_id'], 'exist', 'skipOnError' => true, 'targetClass' => SocCotizacionSegSocial::class, 'targetAttribute' => ['scss_id' => 'scss_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dtdss_id' => 'Dtdss ID',
            'scss_id' => 'Scss ID',
        ];
    }

    /**
     * Gets query for [[Dtdss]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDtdss()
    {
        return $this->hasOne(DetalleDocSegSocial::class, ['dtdss_id' => 'dtdss_id']);
    }

    /**
     * Gets query for [[Scss]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScss()
    {
        return $this->hasOne(SocCotizacionSegSocial::class, ['scss_id' => 'scss_id']);
    }

}
