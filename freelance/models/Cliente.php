<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $cli_id Código identificador del registro
 * @property int $cli_numero Número/Código de Cliente
 * @property string $cli_nombre Nombre o razón social del cliente
 * @property string|null $cli_persona_contacto
 * @property int $tdo_id Tipo documento de identidad
 * @property string|null $cli_docinipais Campo para almacenar las iniciales cuando el tipo de documento de identificación es CIF Intracomunitario
 * @property string $cli_numdocide Número de documento de identidad del socio
 * @property string|null $cli_feccaddoc Fecha de caducidad del documento de identidad del cliente (solo aplica para NIE)
 * @property string|null $cli_tel1 Teléfono del cliente
 * @property string|null $cli_tel2 Segundo teléfono del cliente
 * @property string|null $cli_direccion Dirección del cliente
 * @property int $pai_id
 * @property int|null $prv_id Provincia
 * @property string|null $cli_poblacion Población
 * @property string|null $cli_codpostal Código postal del cliente
 * @property string|null $cli_email Correo electrónico del cliente
 * @property string|null $cli_cuenta_contable Cuenta contable del cliente
 * @property int|null $iva_id IVA
 * @property int $fdp_id
 * @property int $soc_id Socio asociado al cliente
 * @property string|null $cli_observaciones Observaciones
 * @property string $cli_estado Estado del cliente: Activo, Inactivo
 * @property int $cli_exportado Campo que indica si el cliente ya fué exportado: 0 - No, 1 - Si
 * @property int $cli_eliminado
 *
 * @property CliAltaBaja[] $cliAltaBajas
 * @property Factura[] $facturas
 * @property FormaDePago $fdp
 * @property Iva $iva
 * @property Pais $pai
 * @property Presupuesto[] $presupuestos
 * @property Provincia $prv
 * @property Socio $soc
 * @property TipoDocIdentidad $tdo
 */
class Cliente extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const CLI_ESTADO_ACTIVO = 'Activo';
    const CLI_ESTADO_INACTIVO = 'Inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cli_persona_contacto', 'cli_docinipais', 'cli_feccaddoc', 'cli_tel1', 'cli_tel2', 'cli_direccion', 'prv_id', 'cli_poblacion', 'cli_codpostal', 'cli_email', 'cli_cuenta_contable', 'iva_id', 'cli_observaciones'], 'default', 'value' => null],
            [['cli_estado'], 'default', 'value' => 'Activo'],
            [['cli_eliminado'], 'default', 'value' => 0],
            [['cli_numero', 'cli_nombre', 'tdo_id', 'cli_numdocide', 'pai_id', 'fdp_id', 'soc_id'], 'required'],
            [['cli_numero', 'tdo_id', 'pai_id', 'prv_id', 'iva_id', 'fdp_id', 'soc_id', 'cli_exportado', 'cli_eliminado'], 'integer'],
            [['cli_feccaddoc'], 'safe'],
            [['cli_observaciones', 'cli_estado'], 'string'],
            [['cli_nombre', 'cli_persona_contacto', 'cli_direccion', 'cli_poblacion', 'cli_email'], 'string', 'max' => 255],
            [['cli_docinipais'], 'string', 'max' => 3],
            [['cli_numdocide'], 'string', 'max' => 20],
            [['cli_tel1', 'cli_tel2'], 'string', 'max' => 45],
            [['cli_codpostal'], 'string', 'max' => 10],
            [['cli_cuenta_contable'], 'string', 'max' => 7],
            ['cli_estado', 'in', 'range' => array_keys(self::optsCliEstado())],
            [['iva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iva::class, 'targetAttribute' => ['iva_id' => 'iva_id']],
            [['fdp_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormaDePago::class, 'targetAttribute' => ['fdp_id' => 'fdp_id']],
            [['pai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::class, 'targetAttribute' => ['pai_id' => 'pai_id']],
            [['prv_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::class, 'targetAttribute' => ['prv_id' => 'prv_id']],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
            [['tdo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocIdentidad::class, 'targetAttribute' => ['tdo_id' => 'tdo_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cli_id' => 'Cli ID',
            'cli_numero' => 'Cli Numero',
            'cli_nombre' => 'Cli Nombre',
            'cli_persona_contacto' => 'Cli Persona Contacto',
            'tdo_id' => 'Tdo ID',
            'cli_docinipais' => 'Cli Docinipais',
            'cli_numdocide' => 'Cli Numdocide',
            'cli_feccaddoc' => 'Cli Feccaddoc',
            'cli_tel1' => 'Cli Tel1',
            'cli_tel2' => 'Cli Tel2',
            'cli_direccion' => 'Cli Direccion',
            'pai_id' => 'Pai ID',
            'prv_id' => 'Prv ID',
            'cli_poblacion' => 'Cli Poblacion',
            'cli_codpostal' => 'Cli Codpostal',
            'cli_email' => 'Cli Email',
            'cli_cuenta_contable' => 'Cli Cuenta Contable',
            'iva_id' => 'Iva ID',
            'fdp_id' => 'Fdp ID',
            'soc_id' => 'Soc ID',
            'cli_observaciones' => 'Cli Observaciones',
            'cli_estado' => 'Cli Estado',
            'cli_exportado' => 'Cli Exportado',
            'cli_eliminado' => 'Cli Eliminado',
        ];
    }

    /**
     * Gets query for [[CliAltaBajas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliAltaBajas()
    {
        return $this->hasMany(CliAltaBaja::class, ['cli_id' => 'cli_id']);
    }

    /**
     * Gets query for [[Facturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacturas()
    {
        return $this->hasMany(Factura::class, ['cli_id' => 'cli_id']);
    }

    /**
     * Gets query for [[Fdp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFdp()
    {
        return $this->hasOne(FormaDePago::class, ['fdp_id' => 'fdp_id']);
    }

    /**
     * Gets query for [[Iva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIva()
    {
        return $this->hasOne(Iva::class, ['iva_id' => 'iva_id']);
    }

    /**
     * Gets query for [[Pai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPai()
    {
        return $this->hasOne(Pais::class, ['pai_id' => 'pai_id']);
    }

    /**
     * Gets query for [[Presupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPresupuestos()
    {
        return $this->hasMany(Presupuesto::class, ['cli_id' => 'cli_id']);
    }

    /**
     * Gets query for [[Prv]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrv()
    {
        return $this->hasOne(Provincia::class, ['prv_id' => 'prv_id']);
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
     * Gets query for [[Tdo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTdo()
    {
        return $this->hasOne(TipoDocIdentidad::class, ['tdo_id' => 'tdo_id']);
    }


    /**
     * column cli_estado ENUM value labels
     * @return string[]
     */
    public static function optsCliEstado()
    {
        return [
            self::CLI_ESTADO_ACTIVO => 'Activo',
            self::CLI_ESTADO_INACTIVO => 'Inactivo',
        ];
    }

    /**
     * @return string
     */
    public function displayCliEstado()
    {
        return self::optsCliEstado()[$this->cli_estado];
    }

    /**
     * @return bool
     */
    public function isCliEstadoActivo()
    {
        return $this->cli_estado === self::CLI_ESTADO_ACTIVO;
    }

    public function setCliEstadoToActivo()
    {
        $this->cli_estado = self::CLI_ESTADO_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isCliEstadoInactivo()
    {
        return $this->cli_estado === self::CLI_ESTADO_INACTIVO;
    }

    public function setCliEstadoToInactivo()
    {
        $this->cli_estado = self::CLI_ESTADO_INACTIVO;
    }
}
