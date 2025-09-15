<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cuentas_presupuesto".
 *
 * @property int $ban_id
 * @property int $pre_id
 *
 * @property Banco $ban
 * @property Presupuesto $pre
 */
class CuentasPresupuesto extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cuentas_presupuesto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ban_id', 'pre_id'], 'required'],
            [['ban_id', 'pre_id'], 'integer'],
            [['ban_id', 'pre_id'], 'unique', 'targetAttribute' => ['ban_id', 'pre_id']],
            [['ban_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banco::class, 'targetAttribute' => ['ban_id' => 'ban_id']],
            [['pre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Presupuesto::class, 'targetAttribute' => ['pre_id' => 'pre_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ban_id' => 'Ban ID',
            'pre_id' => 'Pre ID',
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
     * Gets query for [[Pre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPre()
    {
        return $this->hasOne(Presupuesto::class, ['pre_id' => 'pre_id']);
    }

}
