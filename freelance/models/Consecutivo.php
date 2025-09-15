<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consecutivo".
 *
 * @property string $con_serie Serie
 * @property int $con_consecutivo Consecutivo
 */
class Consecutivo extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const CON_SERIE_F = 'F';
    const CON_SERIE_L = 'L';
    const CON_SERIE_S = 'S';
    const CON_SERIE_C = 'C';
    const CON_SERIE_P = 'P';
    const CON_SERIE_PL = 'PL';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consecutivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['con_serie', 'con_consecutivo'], 'required'],
            [['con_serie'], 'string'],
            [['con_consecutivo'], 'integer'],
            ['con_serie', 'in', 'range' => array_keys(self::optsConSerie())],
            [['con_serie', 'con_consecutivo'], 'unique', 'targetAttribute' => ['con_serie', 'con_consecutivo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'con_serie' => 'Con Serie',
            'con_consecutivo' => 'Con Consecutivo',
        ];
    }


    /**
     * column con_serie ENUM value labels
     * @return string[]
     */
    public static function optsConSerie()
    {
        return [
            self::CON_SERIE_F => 'F',
            self::CON_SERIE_L => 'L',
            self::CON_SERIE_S => 'S',
            self::CON_SERIE_C => 'C',
            self::CON_SERIE_P => 'P',
            self::CON_SERIE_PL => 'PL',
        ];
    }

    /**
     * @return string
     */
    public function displayConSerie()
    {
        return self::optsConSerie()[$this->con_serie];
    }

    /**
     * @return bool
     */
    public function isConSerieF()
    {
        return $this->con_serie === self::CON_SERIE_F;
    }

    public function setConSerieToF()
    {
        $this->con_serie = self::CON_SERIE_F;
    }

    /**
     * @return bool
     */
    public function isConSerieL()
    {
        return $this->con_serie === self::CON_SERIE_L;
    }

    public function setConSerieToL()
    {
        $this->con_serie = self::CON_SERIE_L;
    }

    /**
     * @return bool
     */
    public function isConSerieS()
    {
        return $this->con_serie === self::CON_SERIE_S;
    }

    public function setConSerieToS()
    {
        $this->con_serie = self::CON_SERIE_S;
    }

    /**
     * @return bool
     */
    public function isConSerieC()
    {
        return $this->con_serie === self::CON_SERIE_C;
    }

    public function setConSerieToC()
    {
        $this->con_serie = self::CON_SERIE_C;
    }

    /**
     * @return bool
     */
    public function isConSerieP()
    {
        return $this->con_serie === self::CON_SERIE_P;
    }

    public function setConSerieToP()
    {
        $this->con_serie = self::CON_SERIE_P;
    }

    /**
     * @return bool
     */
    public function isConSeriePl()
    {
        return $this->con_serie === self::CON_SERIE_PL;
    }

    public function setConSerieToPl()
    {
        $this->con_serie = self::CON_SERIE_PL;
    }
}
