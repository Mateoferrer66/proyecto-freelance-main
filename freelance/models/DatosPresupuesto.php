<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "datos_presupuesto".
 *
 * @property int $dap_id
 * @property int $pre_id
 * @property string $dap_tipo Tipo de datos: indica si corresponden al emisor o al receptor del presupuesto
 * @property string $dap_nombre Nombre de la persona o empresa
 * @property string $dap_direccion Dirección de la persona o empresa
 * @property string $dap_cod_postal Código postal de la persona o empresa
 * @property int|null $prv_id Provincia
 * @property string|null $dap_poblacion Población
 * @property int $tdo_id Tipo documento de identidad
 * @property string $dap_numdocide Número del documento de identificación de la persona o empresa
 *
 * @property Presupuesto $pre
 * @property Provincia $prv
 * @property TipoDocIdentidad $tdo
 */
class DatosPresupuesto extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const DAP_TIPO_EMISOR = 'Emisor';
    const DAP_TIPO_RECEPTOR = 'Receptor';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'datos_presupuesto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prv_id', 'dap_poblacion'], 'default', 'value' => null],
            [['pre_id', 'dap_tipo', 'dap_nombre', 'dap_direccion', 'dap_cod_postal', 'tdo_id', 'dap_numdocide'], 'required'],
            [['pre_id', 'prv_id', 'tdo_id'], 'integer'],
            [['dap_tipo'], 'string'],
            [['dap_nombre', 'dap_direccion', 'dap_poblacion'], 'string', 'max' => 255],
            [['dap_cod_postal'], 'string', 'max' => 10],
            [['dap_numdocide'], 'string', 'max' => 20],
            ['dap_tipo', 'in', 'range' => array_keys(self::optsDapTipo())],
            [['pre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Presupuesto::class, 'targetAttribute' => ['pre_id' => 'pre_id']],
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
            'dap_id' => 'Dap ID',
            'pre_id' => 'Pre ID',
            'dap_tipo' => 'Dap Tipo',
            'dap_nombre' => 'Dap Nombre',
            'dap_direccion' => 'Dap Direccion',
            'dap_cod_postal' => 'Dap Cod Postal',
            'prv_id' => 'Prv ID',
            'dap_poblacion' => 'Dap Poblacion',
            'tdo_id' => 'Tdo ID',
            'dap_numdocide' => 'Dap Numdocide',
        ];
    }

    /**
     * Gets query for [[Pre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPre()
    {
        return $this->hasOne(Presupuesto::class, ['pre_id' => 'pre_id']);
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
     * column dap_tipo ENUM value labels
     * @return string[]
     */
    public static function optsDapTipo()
    {
        return [
            self::DAP_TIPO_EMISOR => 'Emisor',
            self::DAP_TIPO_RECEPTOR => 'Receptor',
        ];
    }

    /**
     * @return string
     */
    public function displayDapTipo()
    {
        return self::optsDapTipo()[$this->dap_tipo];
    }

    /**
     * @return bool
     */
    public function isDapTipoEmisor()
    {
        return $this->dap_tipo === self::DAP_TIPO_EMISOR;
    }

    public function setDapTipoToEmisor()
    {
        $this->dap_tipo = self::DAP_TIPO_EMISOR;
    }

    /**
     * @return bool
     */
    public function isDapTipoReceptor()
    {
        return $this->dap_tipo === self::DAP_TIPO_RECEPTOR;
    }

    public function setDapTipoToReceptor()
    {
        $this->dap_tipo = self::DAP_TIPO_RECEPTOR;
    }
}
