<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "participacion".
 *
 * @property int $par_numero Consecutivo del número de participaciones
 */
class Participacion extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'participacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['par_numero'], 'required'],
            [['par_numero'], 'integer'],
            [['par_numero'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'par_numero' => 'Par Numero',
        ];
    }

}
