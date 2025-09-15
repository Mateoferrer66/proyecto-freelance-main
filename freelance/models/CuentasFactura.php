<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cuentas_factura".
 *
 * @property int $ban_id
 * @property int $fac_id
 *
 * @property Banco $ban
 * @property Factura $fac
 */
class CuentasFactura extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cuentas_factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ban_id', 'fac_id'], 'required'],
            [['ban_id', 'fac_id'], 'integer'],
            [['ban_id', 'fac_id'], 'unique', 'targetAttribute' => ['ban_id', 'fac_id']],
            [['ban_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banco::class, 'targetAttribute' => ['ban_id' => 'ban_id']],
            [['fac_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factura::class, 'targetAttribute' => ['fac_id' => 'fac_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ban_id' => 'Ban ID',
            'fac_id' => 'Fac ID',
        ];
    }

    /**
     * Gets query for [[Ban]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBan()
    {
        return $this->hasOne(Banco::class, ['ban_id' => 'ban_id']);
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
