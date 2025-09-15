<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concepto_facturacion".
 *
 * @property int $cof_id Código identificador del registro
 * @property int|null $iva_id IVA que aplica para el concepto
 * @property string $cof_codigo Código del concepto de facturación
 * @property string $cof_nombre Nombre del concepto de facturación
 * @property string $cof_clasificacion Tipo de concepto: estandar u opcional
 * @property int $cof_eliminado Campo para indicar si el concepto se encuentra eliminado: 1 - Si, 0 - No
 *
 * @property DetalleFactura[] $detalleFacturas
 * @property DetallePresupuesto[] $detallePresupuestos
 * @property Iva $iva
 */
class ConceptoFacturacion extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const COF_CLASIFICACION_ESTANDAR = 'estandar';
    const COF_CLASIFICACION_OPCIONAL = 'opcional';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concepto_facturacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iva_id'], 'default', 'value' => null],
            [['cof_clasificacion'], 'default', 'value' => 'estandar'],
            [['cof_eliminado'], 'default', 'value' => 0],
            [['iva_id', 'cof_eliminado'], 'integer'],
            [['cof_codigo', 'cof_nombre'], 'required'],
            [['cof_clasificacion'], 'string'],
            [['cof_codigo'], 'string', 'max' => 3],
            [['cof_nombre'], 'string', 'max' => 255],
            ['cof_clasificacion', 'in', 'range' => array_keys(self::optsCofClasificacion())],
            [['iva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iva::class, 'targetAttribute' => ['iva_id' => 'iva_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cof_id' => 'Cof ID',
            'iva_id' => 'Iva ID',
            'cof_codigo' => 'Cof Codigo',
            'cof_nombre' => 'Cof Nombre',
            'cof_clasificacion' => 'Cof Clasificacion',
            'cof_eliminado' => 'Cof Eliminado',
        ];
    }

    /**
     * Gets query for [[DetalleFacturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleFacturas()
    {
        return $this->hasMany(DetalleFactura::class, ['cof_id' => 'cof_id']);
    }

    /**
     * Gets query for [[DetallePresupuestos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetallePresupuestos()
    {
        return $this->hasMany(DetallePresupuesto::class, ['cof_id' => 'cof_id']);
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
     * column cof_clasificacion ENUM value labels
     * @return string[]
     */
    public static function optsCofClasificacion()
    {
        return [
            self::COF_CLASIFICACION_ESTANDAR => 'estandar',
            self::COF_CLASIFICACION_OPCIONAL => 'opcional',
        ];
    }

    /**
     * @return string
     */
    public function displayCofClasificacion()
    {
        return self::optsCofClasificacion()[$this->cof_clasificacion];
    }

    /**
     * @return bool
     */
    public function isCofClasificacionEstandar()
    {
        return $this->cof_clasificacion === self::COF_CLASIFICACION_ESTANDAR;
    }

    public function setCofClasificacionToEstandar()
    {
        $this->cof_clasificacion = self::COF_CLASIFICACION_ESTANDAR;
    }

    /**
     * @return bool
     */
    public function isCofClasificacionOpcional()
    {
        return $this->cof_clasificacion === self::COF_CLASIFICACION_OPCIONAL;
    }

    public function setCofClasificacionToOpcional()
    {
        $this->cof_clasificacion = self::COF_CLASIFICACION_OPCIONAL;
    }
}
