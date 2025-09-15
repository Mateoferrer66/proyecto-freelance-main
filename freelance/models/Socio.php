<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "socio".
 *
 * @property int $soc_id Código identificador del registro
 * @property int|null $cat_id
 * @property int $soc_numero Número/Código de socio
 * @property string $soc_fecha Fecha de creación del socio
 * @property string $soc_nombre Nombre del socio
 * @property string $soc_apellido Apellido del socio
 * @property string $soc_apellido1 Primer apellido del socio
 * @property string|null $soc_apellido2 Segundo apellido del socio
 * @property int $tdo_id Tipo de documento de identidad del socio
 * @property string $soc_numdocide Número de documento de identidad del socio
 * @property string|null $soc_feccaddoc Fecha de caducidad del documento de identidad del socio (solo aplica para NIE)
 * @property string|null $soc_ocupacion Ocupación del socio
 * @property string $soc_fecnacimiento Fecha de nacimiento del socio
 * @property string $soc_sexo Sexo del socio
 * @property string|null $soc_telfijo Teléfono fijo del socio
 * @property string|null $soc_telmovil Teléfono móvil del socio
 * @property string|null $soc_direccion Dirección del socio
 * @property int|null $prv_id
 * @property string|null $soc_poblacion
 * @property string|null $soc_codpostal Código postal del socio
 * @property string|null $soc_email Correo electrónico del socio
 * @property string|null $soc_web Página web del socio
 * @property string $soc_numsegsocial Número de la seguridad social
 * @property string $soc_grcotsegsocial Grupo de cotización seguridad social (por defecto 08)
 * @property int $soc_coefcotizacion Coeficiente de cotización, por defecto 100%. Estos datos se usarán en las altas y bajas a la seguridad social.
 * @property float $soc_basecotizacion Base de cotización, por defecto 25 Euros. Estos datos se usarán en las altas y bajas a la seguridad social.
 * @property string $soc_ctabancaria Cuenta bancaria del socio
 * @property float $soc_porcretirpf Porcentaje de retención de IRPF
 * @property string|null $soc_observaciones Observaciones
 * @property string|null $soc_ficlogo Fichero de logo
 * @property string|null $soc_ficcontrato Fichero de contrato
 * @property string|null $soc_ficdocide Fichero documento de identidad
 * @property string|null $soc_ficotros Fichero otros documentos
 * @property string|null $soc_fiprl
 * @property int $soc_participacion_desde
 * @property int $soc_participacion_hasta
 * @property int $soc_pago_participacion Campo para PARA informar de cotejo del pago de las participaciones
 * @property float|null $soc_deuda Deuda
 * @property string $soc_estado Estado del socio: Activo, Inactivo
 * @property int $soc_exportado Campo para indicar si el socio ya fué exportado: 0 - No, 1 - Si
 * @property int $soc_eliminado Campo que indica si el socio se encuentra eliminado: 1 - Si, 0 - No
 *
 * @property Categoria $cat
 * @property Cliente[] $clientes
 * @property DetalleDocSegSocial[] $detalleDocSegSocials
 * @property Factura[] $facturas
 * @property Liquidacion[] $liquidacions
 * @property Presupuesto[] $presupuestos
 * @property Provincia $prv
 * @property SocAltaBaja[] $socAltaBajas
 * @property SocCotizacionSegSocial[] $socCotizacionSegSocials
 * @property SocCuenta[] $socCuentas
 * @property SocCuotaMensual[] $socCuotaMensuals
 * @property TipoDocIdentidad $tdo
 */
class Socio extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const SOC_SEXO_FEMENINO = 'Femenino';
    const SOC_SEXO_MASCULINO = 'Masculino';
    const SOC_ESTADO_ACTIVO = 'Activo';
    const SOC_ESTADO_INACTIVO = 'Inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'socio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_id', 'soc_apellido2', 'soc_feccaddoc', 'soc_ocupacion', 'soc_telfijo', 'soc_telmovil', 'soc_direccion', 'prv_id', 'soc_poblacion', 'soc_codpostal', 'soc_email', 'soc_web', 'soc_observaciones', 'soc_ficlogo', 'soc_ficcontrato', 'soc_ficdocide', 'soc_ficotros', 'soc_fiprl'], 'default', 'value' => null],
            [['soc_grcotsegsocial'], 'default', 'value' => 8],
            [['soc_coefcotizacion'], 'default', 'value' => 100],
            [['soc_basecotizacion'], 'default', 'value' => 30],
            [['soc_porcretirpf'], 'default', 'value' => 2],
            [['soc_eliminado'], 'default', 'value' => 0],
            [['soc_estado'], 'default', 'value' => 'Activo'],
            [['cat_id', 'soc_numero', 'tdo_id', 'prv_id', 'soc_coefcotizacion', 'soc_participacion_desde', 'soc_participacion_hasta', 'soc_pago_participacion', 'soc_exportado', 'soc_eliminado'], 'integer'],
            [['soc_numero', 'soc_fecha', 'soc_nombre', 'soc_apellido', 'soc_apellido1', 'tdo_id', 'soc_numdocide', 'soc_fecnacimiento', 'soc_sexo', 'soc_numsegsocial', 'soc_ctabancaria'], 'required'],
            [['soc_fecha', 'soc_feccaddoc', 'soc_fecnacimiento'], 'safe'],
            [['soc_sexo', 'soc_observaciones', 'soc_estado'], 'string'],
            [['soc_basecotizacion', 'soc_porcretirpf', 'soc_deuda'], 'number'],
            [['soc_nombre', 'soc_apellido', 'soc_ocupacion', 'soc_direccion', 'soc_poblacion', 'soc_email', 'soc_web', 'soc_ficlogo', 'soc_ficcontrato', 'soc_ficdocide', 'soc_ficotros', 'soc_fiprl'], 'string', 'max' => 255],
            [['soc_apellido1', 'soc_apellido2'], 'string', 'max' => 127],
            [['soc_numdocide'], 'string', 'max' => 20],
            [['soc_telfijo', 'soc_telmovil', 'soc_numsegsocial'], 'string', 'max' => 45],
            [['soc_codpostal'], 'string', 'max' => 10],
            [['soc_grcotsegsocial'], 'string', 'max' => 3],
            [['soc_ctabancaria'], 'string', 'max' => 24],
            ['soc_sexo', 'in', 'range' => array_keys(self::optsSocSexo())],
            ['soc_estado', 'in', 'range' => array_keys(self::optsSocEstado())],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['cat_id' => 'cat_id']],
            [['prv_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provincia::class, 'targetAttribute' => ['prv_id' => 'prv_id']],
            [['tdo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocIdentidad::class, 'targetAttribute' => ['tdo_id' => 'tdo_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'soc_id' => 'Soc ID',
            'cat_id' => 'Cat ID',
            'soc_numero' => 'Soc Numero',
            'soc_fecha' => 'Soc Fecha',
            'soc_nombre' => 'Soc Nombre',
            'soc_apellido' => 'Soc Apellido',
            'soc_apellido1' => 'Soc Apellido1',
            'soc_apellido2' => 'Soc Apellido2',
            'tdo_id' => 'Tdo ID',
            'soc_numdocide' => 'Soc Numdocide',
            'soc_feccaddoc' => 'Soc Feccaddoc',
            'soc_ocupacion' => 'Soc Ocupacion',
            'soc_fecnacimiento' => 'Soc Fecnacimiento',
            'soc_sexo' => 'Soc Sexo',
            'soc_telfijo' => 'Soc Telfijo',
            'soc_telmovil' => 'Soc Telmovil',
            'soc_direccion' => 'Soc Direccion',
            'prv_id' => 'Prv ID',
            'soc_poblacion' => 'Soc Poblacion',
            'soc_codpostal' => 'Soc Codpostal',
            'soc_email' => 'Soc Email',
            'soc_web' => 'Soc Web',
            'soc_numsegsocial' => 'Soc Numsegsocial',
            'soc_grcotsegsocial' => 'Soc Grcotsegsocial',
            'soc_coefcotizacion' => 'Soc Coefcotizacion',
            'soc_basecotizacion' => 'Soc Basecotizacion',
            'soc_ctabancaria' => 'Soc Ctabancaria',
            'soc_porcretirpf' => 'Soc Porcretirpf',
            'soc_observaciones' => 'Soc Observaciones',
            'soc_ficlogo' => 'Soc Ficlogo',
            'soc_ficcontrato' => 'Soc Ficcontrato',
            'soc_ficdocide' => 'Soc Ficdocide',
            'soc_ficotros' => 'Soc Ficotros',
            'soc_fiprl' => 'Soc Fiprl',
            'soc_participacion_desde' => 'Soc Participacion Desde',
            'soc_participacion_hasta' => 'Soc Participacion Hasta',
            'soc_pago_participacion' => 'Soc Pago Participacion',
            'soc_deuda' => 'Soc Deuda',
            'soc_estado' => 'Soc Estado',
            'soc_exportado' => 'Soc Exportado',
            'soc_eliminado' => 'Soc Eliminado',
        ];
    }

    /**
     * Gets query for [[Cat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Categoria::class, ['cat_id' => 'cat_id']);
    }

    /**
     * Gets query for [[Clientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Gets query for [[DetalleDocSegSocials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleDocSegSocials()
    {
        return $this->hasMany(DetalleDocSegSocial::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Gets query for [[Facturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacturas()
    {
        return $this->hasMany(Factura::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Gets query for [[Liquidacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidacions()
    {
        return $this->hasMany(Liquidacion::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Gets query for [[Presupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPresupuestos()
    {
        return $this->hasMany(Presupuesto::class, ['soc_id' => 'soc_id']);
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
     * Gets query for [[SocAltaBajas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocAltaBajas()
    {
        return $this->hasMany(SocAltaBaja::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Gets query for [[SocCotizacionSegSocials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocCotizacionSegSocials()
    {
        return $this->hasMany(SocCotizacionSegSocial::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Gets query for [[SocCuentas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocCuentas()
    {
        return $this->hasMany(SocCuenta::class, ['soc_id' => 'soc_id']);
    }

    /**
     * Gets query for [[SocCuotaMensuals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocCuotaMensuals()
    {
        return $this->hasMany(SocCuotaMensual::class, ['soc_id' => 'soc_id']);
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
     * column soc_sexo ENUM value labels
     * @return string[]
     */
    public static function optsSocSexo()
    {
        return [
            self::SOC_SEXO_FEMENINO => 'Femenino',
            self::SOC_SEXO_MASCULINO => 'Masculino',
        ];
    }

    /**
     * column soc_estado ENUM value labels
     * @return string[]
     */
    public static function optsSocEstado()
    {
        return [
            self::SOC_ESTADO_ACTIVO => 'Activo',
            self::SOC_ESTADO_INACTIVO => 'Inactivo',
        ];
    }

    /**
     * @return string
     */
    public function displaySocSexo()
    {
        return self::optsSocSexo()[$this->soc_sexo];
    }

    /**
     * @return bool
     */
    public function isSocSexoFemenino()
    {
        return $this->soc_sexo === self::SOC_SEXO_FEMENINO;
    }

    public function setSocSexoToFemenino()
    {
        $this->soc_sexo = self::SOC_SEXO_FEMENINO;
    }

    /**
     * @return bool
     */
    public function isSocSexoMasculino()
    {
        return $this->soc_sexo === self::SOC_SEXO_MASCULINO;
    }

    public function setSocSexoToMasculino()
    {
        $this->soc_sexo = self::SOC_SEXO_MASCULINO;
    }

    /**
     * @return string
     */
    public function displaySocEstado()
    {
        return self::optsSocEstado()[$this->soc_estado];
    }

    /**
     * @return bool
     */
    public function isSocEstadoActivo()
    {
        return $this->soc_estado === self::SOC_ESTADO_ACTIVO;
    }

    public function setSocEstadoToActivo()
    {
        $this->soc_estado = self::SOC_ESTADO_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isSocEstadoInactivo()
    {
        return $this->soc_estado === self::SOC_ESTADO_INACTIVO;
    }

    public function setSocEstadoToInactivo()
    {
        $this->soc_estado = self::SOC_ESTADO_INACTIVO;
    }
}
