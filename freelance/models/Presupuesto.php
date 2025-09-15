<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "presupuesto".
 *
 * @property int $pre_id Código identificador del registro
 * @property string $pre_numero Número del presupuesto
 * @property string $pre_logo Logo a colocar en el formato del presupuesto
 * @property string $pre_fecha Fecha del presupuesto
 * @property string $pre_language Idioma
 * @property int $cli_id Código del cliente
 * @property int $soc_id Código del socio
 * @property int $fdp_id Forma de pago
 * @property float $pre_subtotal Valor subtotal del presupuesto
 * @property float $pre_iva Valor del iva del presupuesto
 * @property float $pre_gastos_suplidos Valor total de gastos suplidos
 * @property float $pre_total Valor total del presupuesto
 * @property string|null $pre_observaciones Observaciones
 * @property int $pre_eliminado Campo que indica si el presupuesto se encuentra eliminado
 *
 * @property Banco[] $bans
 * @property Cliente $cli
 * @property CuentasPresupuesto[] $cuentasPresupuestos
 * @property DatosPresupuesto[] $datosPresupuestos
 * @property DetallePresupuesto[] $detallePresupuestos
 * @property FormaDePago $fdp
 * @property Socio $soc
 */
class Presupuesto extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const PRE_LOGO_SOCIO = 'socio';
    const PRE_LOGO_EMPRESA = 'empresa';
    const PRE_LANGUAGE_EN = 'en';
    const PRE_LANGUAGE_ES = 'es';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'presupuesto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pre_observaciones'], 'default', 'value' => null],
            [['pre_language'], 'default', 'value' => 'es'],
            [['pre_eliminado'], 'default', 'value' => 0],
            [['pre_numero', 'pre_logo', 'pre_fecha', 'cli_id', 'soc_id', 'fdp_id'], 'required'],
            [['pre_logo', 'pre_language', 'pre_observaciones'], 'string'],
            [['pre_fecha'], 'safe'],
            [['cli_id', 'soc_id', 'fdp_id', 'pre_eliminado'], 'integer'],
            [['pre_subtotal', 'pre_iva', 'pre_gastos_suplidos', 'pre_total'], 'number'],
            [['pre_numero'], 'string', 'max' => 45],
            ['pre_logo', 'in', 'range' => array_keys(self::optsPreLogo())],
            ['pre_language', 'in', 'range' => array_keys(self::optsPreLanguage())],
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
            'pre_id' => 'Pre ID',
            'pre_numero' => 'Pre Numero',
            'pre_logo' => 'Pre Logo',
            'pre_fecha' => 'Pre Fecha',
            'pre_language' => 'Pre Language',
            'cli_id' => 'Cli ID',
            'soc_id' => 'Soc ID',
            'fdp_id' => 'Fdp ID',
            'pre_subtotal' => 'Pre Subtotal',
            'pre_iva' => 'Pre Iva',
            'pre_gastos_suplidos' => 'Pre Gastos Suplidos',
            'pre_total' => 'Pre Total',
            'pre_observaciones' => 'Pre Observaciones',
            'pre_eliminado' => 'Pre Eliminado',
        ];
    }

    /**
     * Gets query for [[Bans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBans()
    {
        return $this->hasMany(Banco::class, ['ban_id' => 'ban_id'])->viaTable('cuentas_presupuesto', ['pre_id' => 'pre_id']);
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
     * Gets query for [[CuentasPresupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuentasPresupuestos()
    {
        return $this->hasMany(CuentasPresupuesto::class, ['pre_id' => 'pre_id']);
    }

    /**
     * Gets query for [[DatosPresupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDatosPresupuestos()
    {
        return $this->hasMany(DatosPresupuesto::class, ['pre_id' => 'pre_id']);
    }

    /**
     * Gets query for [[DetallePresupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetallePresupuestos()
    {
        return $this->hasMany(DetallePresupuesto::class, ['pre_id' => 'pre_id']);
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
     * Gets query for [[Soc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoc()
    {
        return $this->hasOne(Socio::class, ['soc_id' => 'soc_id']);
    }


    /**
     * column pre_logo ENUM value labels
     * @return string[]
     */
    public static function optsPreLogo()
    {
        return [
            self::PRE_LOGO_SOCIO => 'socio',
            self::PRE_LOGO_EMPRESA => 'empresa',
        ];
    }

    /**
     * column pre_language ENUM value labels
     * @return string[]
     */
    public static function optsPreLanguage()
    {
        return [
            self::PRE_LANGUAGE_EN => 'en',
            self::PRE_LANGUAGE_ES => 'es',
        ];
    }

    /**
     * @return string
     */
    public function displayPreLogo()
    {
        return self::optsPreLogo()[$this->pre_logo];
    }

    /**
     * @return bool
     */
    public function isPreLogoSocio()
    {
        return $this->pre_logo === self::PRE_LOGO_SOCIO;
    }

    public function setPreLogoToSocio()
    {
        $this->pre_logo = self::PRE_LOGO_SOCIO;
    }

    /**
     * @return bool
     */
    public function isPreLogoEmpresa()
    {
        return $this->pre_logo === self::PRE_LOGO_EMPRESA;
    }

    public function setPreLogoToEmpresa()
    {
        $this->pre_logo = self::PRE_LOGO_EMPRESA;
    }

    /**
     * @return string
     */
    public function displayPreLanguage()
    {
        return self::optsPreLanguage()[$this->pre_language];
    }

    /**
     * @return bool
     */
    public function isPreLanguageEn()
    {
        return $this->pre_language === self::PRE_LANGUAGE_EN;
    }

    public function setPreLanguageToEn()
    {
        $this->pre_language = self::PRE_LANGUAGE_EN;
    }

    /**
     * @return bool
     */
    public function isPreLanguageEs()
    {
        return $this->pre_language === self::PRE_LANGUAGE_ES;
    }

    public function setPreLanguageToEs()
    {
        $this->pre_language = self::PRE_LANGUAGE_ES;
    }
}
