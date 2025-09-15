<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cli_alta_baja".
 *
 * @property int $cab_id Código identificador del registro
 * @property int $cli_id Cliente
 * @property int $usu_id
 * @property string $cab_accion Tipo de acción que se realiza sobre el cliente: Alta, Baja
 * @property string $cab_fecha Fecha en que se da de alta o de baja el cliente
 * @property string $cab_observaciones Motivo por el cual se da de alta o de baja el cliente
 *
 * @property Cliente $cli
 * @property Usuario $usu
 */
class CliAltaBaja extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const CAB_ACCION_ALTA = 'Alta';
    const CAB_ACCION_BAJA = 'Baja';
    const CAB_ACCION_ACTIVO = 'Activo';
    const CAB_ACCION_INACTIVO = 'Inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cli_alta_baja';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cli_id', 'usu_id', 'cab_accion', 'cab_fecha', 'cab_observaciones'], 'required'],
            [['cli_id', 'usu_id'], 'integer'],
            [['cab_accion', 'cab_observaciones'], 'string'],
            [['cab_fecha'], 'safe'],
            ['cab_accion', 'in', 'range' => array_keys(self::optsCabAccion())],
            [['cli_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::class, 'targetAttribute' => ['cli_id' => 'cli_id']],
            [['usu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usu_id' => 'usu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cab_id' => 'Cab ID',
            'cli_id' => 'Cli ID',
            'usu_id' => 'Usu ID',
            'cab_accion' => 'Cab Accion',
            'cab_fecha' => 'Cab Fecha',
            'cab_observaciones' => 'Cab Observaciones',
        ];
    }

    /**
     * Gets query for [[Cli]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCli()
    {
        return $this->hasOne(Cliente::class, ['cli_id' => 'cli_id']);
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
     * column cab_accion ENUM value labels
     * @return string[]
     */
    public static function optsCabAccion()
    {
        return [
            self::CAB_ACCION_ALTA => 'Alta',
            self::CAB_ACCION_BAJA => 'Baja',
            self::CAB_ACCION_ACTIVO => 'Activo',
            self::CAB_ACCION_INACTIVO => 'Inactivo',
        ];
    }

    /**
     * @return string
     */
    public function displayCabAccion()
    {
        return self::optsCabAccion()[$this->cab_accion];
    }

    /**
     * @return bool
     */
    public function isCabAccionAlta()
    {
        return $this->cab_accion === self::CAB_ACCION_ALTA;
    }

    public function setCabAccionToAlta()
    {
        $this->cab_accion = self::CAB_ACCION_ALTA;
    }

    /**
     * @return bool
     */
    public function isCabAccionBaja()
    {
        return $this->cab_accion === self::CAB_ACCION_BAJA;
    }

    public function setCabAccionToBaja()
    {
        $this->cab_accion = self::CAB_ACCION_BAJA;
    }

    /**
     * @return bool
     */
    public function isCabAccionActivo()
    {
        return $this->cab_accion === self::CAB_ACCION_ACTIVO;
    }

    public function setCabAccionToActivo()
    {
        $this->cab_accion = self::CAB_ACCION_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isCabAccionInactivo()
    {
        return $this->cab_accion === self::CAB_ACCION_INACTIVO;
    }

    public function setCabAccionToInactivo()
    {
        $this->cab_accion = self::CAB_ACCION_INACTIVO;
    }
}
