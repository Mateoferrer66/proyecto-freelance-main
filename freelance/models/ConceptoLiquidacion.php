<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "concepto_liquidacion".
 *
 * @property int $col_id Código identificador del registro
 * @property string $col_nombre Nombre del concepto de liquidación
 * @property string $col_clasificacion
 * @property string $col_tipo Campo para indicar si el concepto tiene un porcentaje asociado o si tiene un valor libre
 * @property float|null $col_porcentaje Porcentaje asociado al concepto de liquidación
 * @property float|null $col_valor Valor asociado al concepto de liquidación
 * @property int $col_eliminado Campo para indicar si el concepto se encuentra eliminado: 1 - Si, 0 - No
 *
 * @property DetalleLiquidacion[] $detalleLiquidacions
 */
class ConceptoLiquidacion extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const COL_CLASIFICACION_ESTANDAR = 'estandar';
    const COL_CLASIFICACION_OPCIONAL = 'opcional';
    const COL_TIPO_PORCENTAJE = 'porcentaje';
    const COL_TIPO_VALOR = 'valor';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'concepto_liquidacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['col_porcentaje', 'col_valor'], 'default', 'value' => null],
            [['col_clasificacion'], 'default', 'value' => 'estandar'],
            [['col_tipo'], 'default', 'value' => 'porcentaje'],
            [['col_eliminado'], 'default', 'value' => 0],
            [['col_nombre'], 'required'],
            [['col_clasificacion', 'col_tipo'], 'string'],
            [['col_porcentaje', 'col_valor'], 'number'],
            [['col_eliminado'], 'integer'],
            [['col_nombre'], 'string', 'max' => 255],
            ['col_clasificacion', 'in', 'range' => array_keys(self::optsColClasificacion())],
            ['col_tipo', 'in', 'range' => array_keys(self::optsColTipo())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'col_id' => 'Col ID',
            'col_nombre' => 'Col Nombre',
            'col_clasificacion' => 'Col Clasificacion',
            'col_tipo' => 'Col Tipo',
            'col_porcentaje' => 'Col Porcentaje',
            'col_valor' => 'Col Valor',
            'col_eliminado' => 'Col Eliminado',
        ];
    }

    /**
     * Gets query for [[DetalleLiquidacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleLiquidacions()
    {
        return $this->hasMany(DetalleLiquidacion::class, ['col_id' => 'col_id']);
    }


    /**
     * column col_clasificacion ENUM value labels
     * @return string[]
     */
    public static function optsColClasificacion()
    {
        return [
            self::COL_CLASIFICACION_ESTANDAR => 'estandar',
            self::COL_CLASIFICACION_OPCIONAL => 'opcional',
        ];
    }

    /**
     * column col_tipo ENUM value labels
     * @return string[]
     */
    public static function optsColTipo()
    {
        return [
            self::COL_TIPO_PORCENTAJE => 'porcentaje',
            self::COL_TIPO_VALOR => 'valor',
        ];
    }

    /**
     * @return string
     */
    public function displayColClasificacion()
    {
        return self::optsColClasificacion()[$this->col_clasificacion];
    }

    /**
     * @return bool
     */
    public function isColClasificacionEstandar()
    {
        return $this->col_clasificacion === self::COL_CLASIFICACION_ESTANDAR;
    }

    public function setColClasificacionToEstandar()
    {
        $this->col_clasificacion = self::COL_CLASIFICACION_ESTANDAR;
    }

    /**
     * @return bool
     */
    public function isColClasificacionOpcional()
    {
        return $this->col_clasificacion === self::COL_CLASIFICACION_OPCIONAL;
    }

    public function setColClasificacionToOpcional()
    {
        $this->col_clasificacion = self::COL_CLASIFICACION_OPCIONAL;
    }

    /**
     * @return string
     */
    public function displayColTipo()
    {
        return self::optsColTipo()[$this->col_tipo];
    }

    /**
     * @return bool
     */
    public function isColTipoPorcentaje()
    {
        return $this->col_tipo === self::COL_TIPO_PORCENTAJE;
    }

    public function setColTipoToPorcentaje()
    {
        $this->col_tipo = self::COL_TIPO_PORCENTAJE;
    }

    /**
     * @return bool
     */
    public function isColTipoValor()
    {
        return $this->col_tipo === self::COL_TIPO_VALOR;
    }

    public function setColTipoToValor()
    {
        $this->col_tipo = self::COL_TIPO_VALOR;
    }
}
