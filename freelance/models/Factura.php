<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factura".
 *
 * @property int $fac_id Código identificador del registro
 * @property int $fac_numero Número de la factura
 * @property string $fac_logo Logo a colocar en el formato de la factura
 * @property string $fac_fecha Fecha de la factura
 * @property string $fac_language
 * @property string $fac_money
 * @property int $cli_id Código del cliente
 * @property int $soc_id Código del socio
 * @property int $fdp_id Forma de pago
 * @property string $fac_estado Estado de la factura
 * @property string $fac_situacion Situación de la factura
 * @property string|null $fac_fecha_situacion Fecha en que se pone situacion a la factura
 * @property float $fac_subtotal Valor subtotal de la factura
 * @property float $fac_iva Valor del iva de la factura
 * @property float $fac_gastos_suplidos Valor total de gastos suplidos
 * @property float $fac_total Valor total de la factura
 * @property string|null $fac_observaciones Observaciones
 * @property int $fac_exportada Campo para indicar si la factura fué exportada: 0 - No, 1 - Si
 * @property int $fac_eliminada Campo que indica si la factura se encuentra eliminada
 *
 * @property Banco[] $bans
 * @property Cliente $cli
 * @property CuentasFactura[] $cuentasFacturas
 * @property DatosFactura[] $datosFacturas
 * @property DetalleFactura[] $detalleFacturas
 * @property FacturaLiquidacion[] $facturaLiquidacions
 * @property FormaDePago $fdp
 * @property PagosFactura[] $pagosFacturas
 * @property Socio $soc
 */
class Factura extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const FAC_LOGO_SOCIO = 'socio';
    const FAC_LOGO_EMPRESA = 'empresa';
    const FAC_LANGUAGE_EN = 'en';
    const FAC_LANGUAGE_ES = 'es';
    const FAC_MONEY_EUROS = 'Euros';
    const FAC_MONEY = '£';
    const FAC_MONEY_US = 'US$';
    const FAC_ESTADO_SIN_PAGAR = 'Sin Pagar';
    const FAC_ESTADO_LIQUIDADA = 'Liquidada';
    const FAC_ESTADO_LIQUIDADA_PARCIALMENTE = 'Liquidada Parcialmente';
    const FAC_SITUACION_NO_RECLAMADA = 'No Reclamada';
    const FAC_SITUACION_RECLAMADA_AL_CLIENTE = 'Reclamada al Cliente';
    const FAC_SITUACION_RECLAMADA_AL_SOCIO = 'Reclamada al Socio';
    const FAC_SITUACION_CONCURSO_DE_ACREEDORES = 'Concurso de Acreedores';
    const FAC_SITUACION_COBRADA_POR_EL_SOCIO = 'Cobrada por el Socio';
    const FAC_SITUACION_MONITORIO = 'Monitorio';
    const FAC_SITUACION_BUROFAX = 'Burofax';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fac_fecha_situacion', 'fac_observaciones'], 'default', 'value' => null],
            [['fac_logo'], 'default', 'value' => 'empresa'],
            [['fac_language'], 'default', 'value' => 'es'],
            [['fac_money'], 'default', 'value' => 'Euros'],
            [['fac_situacion'], 'default', 'value' => 'No Reclamada'],
            [['fac_eliminada'], 'default', 'value' => 0],
            [['fac_numero', 'fac_fecha', 'cli_id', 'soc_id', 'fdp_id', 'fac_estado'], 'required'],
            [['fac_numero', 'cli_id', 'soc_id', 'fdp_id', 'fac_exportada', 'fac_eliminada'], 'integer'],
            [['fac_logo', 'fac_language', 'fac_money', 'fac_estado', 'fac_situacion', 'fac_observaciones'], 'string'],
            [['fac_fecha', 'fac_fecha_situacion'], 'safe'],
            [['fac_subtotal', 'fac_iva', 'fac_gastos_suplidos', 'fac_total'], 'number'],
            ['fac_logo', 'in', 'range' => array_keys(self::optsFacLogo())],
            ['fac_language', 'in', 'range' => array_keys(self::optsFacLanguage())],
            ['fac_money', 'in', 'range' => array_keys(self::optsFacMoney())],
            ['fac_estado', 'in', 'range' => array_keys(self::optsFacEstado())],
            ['fac_situacion', 'in', 'range' => array_keys(self::optsFacSituacion())],
            [['cli_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::class, 'targetAttribute' => ['cli_id' => 'cli_id']],
            [['fdp_id'], 'exist', 'skipOnError' => true, 'targetClass' => FormaDePago::class, 'targetAttribute' => ['fdp_id' => 'fdp_id']],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fac_id' => 'Fac ID',
            'fac_numero' => 'Fac Numero',
            'fac_logo' => 'Fac Logo',
            'fac_fecha' => 'Fac Fecha',
            'fac_language' => 'Fac Language',
            'fac_money' => 'Fac Money',
            'cli_id' => 'Cli ID',
            'soc_id' => 'Soc ID',
            'fdp_id' => 'Fdp ID',
            'fac_estado' => 'Fac Estado',
            'fac_situacion' => 'Fac Situacion',
            'fac_fecha_situacion' => 'Fac Fecha Situacion',
            'fac_subtotal' => 'Fac Subtotal',
            'fac_iva' => 'Fac Iva',
            'fac_gastos_suplidos' => 'Fac Gastos Suplidos',
            'fac_total' => 'Fac Total',
            'fac_observaciones' => 'Fac Observaciones',
            'fac_exportada' => 'Fac Exportada',
            'fac_eliminada' => 'Fac Eliminada',
        ];
    }

    /**
     * Gets query for [[Bans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBans()
    {
        return $this->hasMany(Banco::class, ['ban_id' => 'ban_id'])->viaTable('cuentas_factura', ['fac_id' => 'fac_id']);
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
     * Gets query for [[CuentasFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuentasFacturas()
    {
        return $this->hasMany(CuentasFactura::class, ['fac_id' => 'fac_id']);
    }

    /**
     * Gets query for [[DatosFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatosFacturas()
    {
        return $this->hasMany(DatosFactura::class, ['fac_id' => 'fac_id']);
    }

    /**
     * Gets query for [[DetalleFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleFacturas()
    {
        return $this->hasMany(DetalleFactura::class, ['fac_id' => 'fac_id']);
    }

    /**
     * Gets query for [[FacturaLiquidacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacturaLiquidacions()
    {
        return $this->hasMany(FacturaLiquidacion::class, ['fac_id' => 'fac_id']);
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
     * Gets query for [[PagosFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPagosFacturas()
    {
        return $this->hasMany(PagosFactura::class, ['fac_id' => 'fac_id']);
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
     * column fac_logo ENUM value labels
     * @return string[]
     */
    public static function optsFacLogo()
    {
        return [
            self::FAC_LOGO_SOCIO => 'socio',
            self::FAC_LOGO_EMPRESA => 'empresa',
        ];
    }

    /**
     * column fac_language ENUM value labels
     * @return string[]
     */
    public static function optsFacLanguage()
    {
        return [
            self::FAC_LANGUAGE_EN => 'en',
            self::FAC_LANGUAGE_ES => 'es',
        ];
    }

    /**
     * column fac_money ENUM value labels
     * @return string[]
     */
    public static function optsFacMoney()
    {
        return [
            self::FAC_MONEY_EUROS => 'Euros',
            self::FAC_MONEY => '£',
            self::FAC_MONEY_US => 'US$',
        ];
    }

    /**
     * column fac_estado ENUM value labels
     * @return string[]
     */
    public static function optsFacEstado()
    {
        return [
            self::FAC_ESTADO_SIN_PAGAR => 'Sin Pagar',
            self::FAC_ESTADO_LIQUIDADA => 'Liquidada',
            self::FAC_ESTADO_LIQUIDADA_PARCIALMENTE => 'Liquidada Parcialmente',
        ];
    }

    /**
     * column fac_situacion ENUM value labels
     * @return string[]
     */
    public static function optsFacSituacion()
    {
        return [
            self::FAC_SITUACION_NO_RECLAMADA => 'No Reclamada',
            self::FAC_SITUACION_RECLAMADA_AL_CLIENTE => 'Reclamada al Cliente',
            self::FAC_SITUACION_RECLAMADA_AL_SOCIO => 'Reclamada al Socio',
            self::FAC_SITUACION_CONCURSO_DE_ACREEDORES => 'Concurso de Acreedores',
            self::FAC_SITUACION_COBRADA_POR_EL_SOCIO => 'Cobrada por el Socio',
            self::FAC_SITUACION_MONITORIO => 'Monitorio',
            self::FAC_SITUACION_BUROFAX => 'Burofax',
        ];
    }

    /**
     * @return string
     */
    public function displayFacLogo()
    {
        return self::optsFacLogo()[$this->fac_logo];
    }

    /**
     * @return bool
     */
    public function isFacLogoSocio()
    {
        return $this->fac_logo === self::FAC_LOGO_SOCIO;
    }

    public function setFacLogoToSocio()
    {
        $this->fac_logo = self::FAC_LOGO_SOCIO;
    }

    /**
     * @return bool
     */
    public function isFacLogoEmpresa()
    {
        return $this->fac_logo === self::FAC_LOGO_EMPRESA;
    }

    public function setFacLogoToEmpresa()
    {
        $this->fac_logo = self::FAC_LOGO_EMPRESA;
    }

    /**
     * @return string
     */
    public function displayFacLanguage()
    {
        return self::optsFacLanguage()[$this->fac_language];
    }

    /**
     * @return bool
     */
    public function isFacLanguageEn()
    {
        return $this->fac_language === self::FAC_LANGUAGE_EN;
    }

    public function setFacLanguageToEn()
    {
        $this->fac_language = self::FAC_LANGUAGE_EN;
    }

    /**
     * @return bool
     */
    public function isFacLanguageEs()
    {
        return $this->fac_language === self::FAC_LANGUAGE_ES;
    }

    public function setFacLanguageToEs()
    {
        $this->fac_language = self::FAC_LANGUAGE_ES;
    }

    /**
     * @return string
     */
    public function displayFacMoney()
    {
        return self::optsFacMoney()[$this->fac_money];
    }

    /**
     * @return bool
     */
    public function isFacMoneyEuros()
    {
        return $this->fac_money === self::FAC_MONEY_EUROS;
    }

    public function setFacMoneyToEuros()
    {
        $this->fac_money = self::FAC_MONEY_EUROS;
    }

    /**
     * @return bool
     */
    public function isFacMoney()
    {
        return $this->fac_money === self::FAC_MONEY;
    }

    public function setFacMoneyTo()
    {
        $this->fac_money = self::FAC_MONEY;
    }

    /**
     * @return bool
     */
    public function isFacMoneyUs()
    {
        return $this->fac_money === self::FAC_MONEY_US;
    }

    public function setFacMoneyToUs()
    {
        $this->fac_money = self::FAC_MONEY_US;
    }

    /**
     * @return string
     */
    public function displayFacEstado()
    {
        return self::optsFacEstado()[$this->fac_estado];
    }

    /**
     * @return bool
     */
    public function isFacEstadoSinPagar()
    {
        return $this->fac_estado === self::FAC_ESTADO_SIN_PAGAR;
    }

    public function setFacEstadoToSinPagar()
    {
        $this->fac_estado = self::FAC_ESTADO_SIN_PAGAR;
    }

    /**
     * @return bool
     */
    public function isFacEstadoLiquidada()
    {
        return $this->fac_estado === self::FAC_ESTADO_LIQUIDADA;
    }

    public function setFacEstadoToLiquidada()
    {
        $this->fac_estado = self::FAC_ESTADO_LIQUIDADA;
    }

    /**
     * @return bool
     */
    public function isFacEstadoLiquidadaParcialmente()
    {
        return $this->fac_estado === self::FAC_ESTADO_LIQUIDADA_PARCIALMENTE;
    }

    public function setFacEstadoToLiquidadaParcialmente()
    {
        $this->fac_estado = self::FAC_ESTADO_LIQUIDADA_PARCIALMENTE;
    }

    /**
     * @return string
     */
    public function displayFacSituacion()
    {
        return self::optsFacSituacion()[$this->fac_situacion];
    }

    /**
     * @return bool
     */
    public function isFacSituacionNoReclamada()
    {
        return $this->fac_situacion === self::FAC_SITUACION_NO_RECLAMADA;
    }

    public function setFacSituacionToNoReclamada()
    {
        $this->fac_situacion = self::FAC_SITUACION_NO_RECLAMADA;
    }

    /**
     * @return bool
     */
    public function isFacSituacionReclamadaAlCliente()
    {
        return $this->fac_situacion === self::FAC_SITUACION_RECLAMADA_AL_CLIENTE;
    }

    public function setFacSituacionToReclamadaAlCliente()
    {
        $this->fac_situacion = self::FAC_SITUACION_RECLAMADA_AL_CLIENTE;
    }

    /**
     * @return bool
     */
    public function isFacSituacionReclamadaAlSocio()
    {
        return $this->fac_situacion === self::FAC_SITUACION_RECLAMADA_AL_SOCIO;
    }

    public function setFacSituacionToReclamadaAlSocio()
    {
        $this->fac_situacion = self::FAC_SITUACION_RECLAMADA_AL_SOCIO;
    }

    /**
     * @return bool
     */
    public function isFacSituacionConcursoDeAcreedores()
    {
        return $this->fac_situacion === self::FAC_SITUACION_CONCURSO_DE_ACREEDORES;
    }

    public function setFacSituacionToConcursoDeAcreedores()
    {
        $this->fac_situacion = self::FAC_SITUACION_CONCURSO_DE_ACREEDORES;
    }

    /**
     * @return bool
     */
    public function isFacSituacionCobradaPorElSocio()
    {
        return $this->fac_situacion === self::FAC_SITUACION_COBRADA_POR_EL_SOCIO;
    }

    public function setFacSituacionToCobradaPorElSocio()
    {
        $this->fac_situacion = self::FAC_SITUACION_COBRADA_POR_EL_SOCIO;
    }

    /**
     * @return bool
     */
    public function isFacSituacionMonitorio()
    {
        return $this->fac_situacion === self::FAC_SITUACION_MONITORIO;
    }

    public function setFacSituacionToMonitorio()
    {
        $this->fac_situacion = self::FAC_SITUACION_MONITORIO;
    }

    /**
     * @return bool
     */
    public function isFacSituacionBurofax()
    {
        return $this->fac_situacion === self::FAC_SITUACION_BUROFAX;
    }

    public function setFacSituacionToBurofax()
    {
        $this->fac_situacion = self::FAC_SITUACION_BUROFAX;
    }
}
