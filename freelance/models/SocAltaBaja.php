<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "soc_alta_baja".
 *
 * @property int $sab_id Código identificador del registro
 * @property int $soc_id
 * @property int $usu_id
 * @property string $sab_accion Tipo de acción que se realiza sobre el socio: Alta, Baja
 * @property string $sab_fecha Fecha en que se da de alta o de baja el socio
 * @property string $sab_observaciones Motivo por el cual se da de alta o de baja el socio
 *
 * @property Socio $soc
 * @property Usuario $usu
 */
class SocAltaBaja extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const SAB_ACCION_ALTA = 'Alta';
    const SAB_ACCION_BAJA = 'Baja';
    const SAB_ACCION_ACTIVO = 'Activo';
    const SAB_ACCION_INACTIVO = 'Inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soc_alta_baja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['soc_id', 'usu_id', 'sab_accion', 'sab_fecha', 'sab_observaciones'], 'required'],
            [['soc_id', 'usu_id'], 'integer'],
            [['sab_accion', 'sab_observaciones'], 'string'],
            [['sab_fecha'], 'safe'],
            ['sab_accion', 'in', 'range' => array_keys(self::optsSabAccion())],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
            [['usu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usu_id' => 'usu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sab_id' => 'Sab ID',
            'soc_id' => 'Soc ID',
            'usu_id' => 'Usu ID',
            'sab_accion' => 'Sab Accion',
            'sab_fecha' => 'Sab Fecha',
            'sab_observaciones' => 'Sab Observaciones',
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

    /**
     * Gets query for [[Usu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsu()
    {
        return $this->hasOne(Usuario::class, ['usu_id' => 'usu_id']);
    }


    /**
     * column sab_accion ENUM value labels
     * @return string[]
     */
    public static function optsSabAccion()
    {
        return [
            self::SAB_ACCION_ALTA => 'Alta',
            self::SAB_ACCION_BAJA => 'Baja',
            self::SAB_ACCION_ACTIVO => 'Activo',
            self::SAB_ACCION_INACTIVO => 'Inactivo',
        ];
    }

    /**
     * @return string
     */
    public function displaySabAccion()
    {
        return self::optsSabAccion()[$this->sab_accion];
    }

    /**
     * @return bool
     */
    public function isSabAccionAlta()
    {
        return $this->sab_accion === self::SAB_ACCION_ALTA;
    }

    public function setSabAccionToAlta()
    {
        $this->sab_accion = self::SAB_ACCION_ALTA;
    }

    /**
     * @return bool
     */
    public function isSabAccionBaja()
    {
        return $this->sab_accion === self::SAB_ACCION_BAJA;
    }

    public function setSabAccionToBaja()
    {
        $this->sab_accion = self::SAB_ACCION_BAJA;
    }

    /**
     * @return bool
     */
    public function isSabAccionActivo()
    {
        return $this->sab_accion === self::SAB_ACCION_ACTIVO;
    }

    public function setSabAccionToActivo()
    {
        $this->sab_accion = self::SAB_ACCION_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isSabAccionInactivo()
    {
        return $this->sab_accion === self::SAB_ACCION_INACTIVO;
    }

    public function setSabAccionToInactivo()
    {
        $this->sab_accion = self::SAB_ACCION_INACTIVO;
    }
}
