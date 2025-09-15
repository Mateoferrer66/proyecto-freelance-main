<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "datos_factura".
 *
 * @property int $daf_id Código identificador del registro
 * @property int $fac_id Factura a la cual pertenecen los datos
 * @property string $daf_tipo Tipo de datos: indica si corresponden al emisor o al receptor de la factura
 * @property string $daf_nombre Nombre de la persona o empresa
 * @property string $daf_direccion Dirección de la persona o empresa
 * @property string $daf_cod_postal Código postal de la persona o empresa
 * @property int|null $pai_id
 * @property int|null $prv_id Provincia
 * @property string|null $daf_poblacion Población
 * @property int $tdo_id Tipo documento de identidad
 * @property string $daf_numdocide Número del documento de identificación de la persona o empresa
 *
 * @property Factura $fac
 * @property Pais $pai
 * @property Provincia $prv
 * @property TipoDocIdentidad $tdo
 */
class DatosFactura extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const DAF_TIPO_EMISOR = 'Emisor';
    const DAF_TIPO_RECEPTOR = 'Receptor';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'datos_factura';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pai_id', 'prv_id', 'daf_poblacion'], 'default', 'value' => null],
            [['daf_direccion'], 'default', 'value' => '-'],
            [['fac_id', 'daf_tipo', 'daf_nombre', 'daf_cod_postal', 'tdo_id', 'daf_numdocide'], 'required'],
            [['fac_id', 'pai_id', 'prv_id', 'tdo_id'], 'integer'],
            [['daf_tipo'], 'string'],
            [['daf_nombre', 'daf_direccion', 'daf_poblacion'], 'string', 'max' => 255],
            [['daf_cod_postal'], 'string', 'max' => 10],
            [['daf_numdocide'], 'string', 'max' => 20],
            ['daf_tipo', 'in', 'range' => array_keys(self::optsDafTipo())],
            [['fac_id'], 'exist', 'skipOnError' => true, 'targetClass' => Factura::class, 'targetAttribute' => ['fac_id' => 'fac_id']],
            [['pai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pais::class, 'targetAttribute' => ['pai_id' => 'pai_id']],
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
            'daf_id' => 'Daf ID',
            'fac_id' => 'Fac ID',
            'daf_tipo' => 'Daf Tipo',
            'daf_nombre' => 'Daf Nombre',
            'daf_direccion' => 'Daf Direccion',
            'daf_cod_postal' => 'Daf Cod Postal',
            'pai_id' => 'Pai ID',
            'prv_id' => 'Prv ID',
            'daf_poblacion' => 'Daf Poblacion',
            'tdo_id' => 'Tdo ID',
            'daf_numdocide' => 'Daf Numdocide',
        ];
    }

    /**
     * Gets query for [[Fac]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFac()
    {
        return $this->hasOne(Factura::class, ['fac_id' => 'fac_id']);
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
     * Gets query for [[Prv]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrv()
    {
        return $this->hasOne(Provincia::class, ['prv_id' => 'prv_id']);
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
     * column daf_tipo ENUM value labels
     * @return string[]
     */
    public static function optsDafTipo()
    {
        return [
            self::DAF_TIPO_EMISOR => 'Emisor',
            self::DAF_TIPO_RECEPTOR => 'Receptor',
        ];
    }

    /**
     * @return string
     */
    public function displayDafTipo()
    {
        return self::optsDafTipo()[$this->daf_tipo];
    }

    /**
     * @return bool
     */
    public function isDafTipoEmisor()
    {
        return $this->daf_tipo === self::DAF_TIPO_EMISOR;
    }

    public function setDafTipoToEmisor()
    {
        $this->daf_tipo = self::DAF_TIPO_EMISOR;
    }

    /**
     * @return bool
     */
    public function isDafTipoReceptor()
    {
        return $this->daf_tipo === self::DAF_TIPO_RECEPTOR;
    }

    public function setDafTipoToReceptor()
    {
        $this->daf_tipo = self::DAF_TIPO_RECEPTOR;
    }
}
