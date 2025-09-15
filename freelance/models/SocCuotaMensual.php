<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "soc_cuota_mensual".
 *
 * @property int $scm_id Código identificador del registro
 * @property int $soc_id Código del socio
 * @property string $scm_date Fecha de pago de la cuota
 * @property float $scm_valor Valor de cuota pagado
 *
 * @property Socio $soc
 */
class SocCuotaMensual extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soc_cuota_mensual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['soc_id', 'scm_date', 'scm_valor'], 'required'],
            [['soc_id'], 'integer'],
            [['scm_date'], 'safe'],
            [['scm_valor'], 'number'],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'scm_id' => 'Scm ID',
            'soc_id' => 'Soc ID',
            'scm_date' => 'Scm Date',
            'scm_valor' => 'Scm Valor',
        ];
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

}
