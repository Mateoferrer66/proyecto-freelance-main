<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property int $cat_id Código identificador del registro
 * @property string $cat_nombre Nombre de la categoría
 * @property int $cat_eliminada Campo para indicar si el registro se encuentra eliminado: 0 - No, 1 - Si
 *
 * @property Socio[] $socios
 */
class Categoria extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_eliminada'], 'default', 'value' => 0],
            [['cat_nombre'], 'required'],
            [['cat_eliminada'], 'integer'],
            [['cat_nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'Cat ID',
            'cat_nombre' => 'Cat Nombre',
            'cat_eliminada' => 'Cat Eliminada',
        ];
    }

    /**
     * Gets query for [[Socios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocios()
    {
        return $this->hasMany(Socio::class, ['cat_id' => 'cat_id']);
    }

}
