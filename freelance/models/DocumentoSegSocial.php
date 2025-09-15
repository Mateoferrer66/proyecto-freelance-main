<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento_seg_social".
 *
 * @property int $dss_id Código identificador del registro
 * @property string $dss_fecha Fecha de creación del documento de alta/baja seguridad social
 * @property string|null $dss_descripcion Descripción/nombre del documento de alta/baja seguridad social
 * @property string $dss_regimen Régimen
 * @property string $dss_ccc Código de Cuenta de Cotización (CCC)
 * @property string $dss_tipo Tipo de empresa
 * @property string $dss_razon_social Nombre de la empresa o entidad
 * @property string|null $dss_afi_alta Nombre del archivo AFI de altas
 * @property string|null $dss_afi_baja Nombre del archivo de Bajas
 * @property string $dss_estado Estado del documento de alta/baja seguridad social: Con Fichero, Sin Fichero
 * @property int $dss_doc_alta Campo para indicar si es un documento de alta
 * @property int $dss_doc_baja Campo para indicar si es un documento de baja
 * @property int $dss_eliminado
 *
 * @property DetalleDocSegSocial[] $detalleDocSegSocials
 */
class DocumentoSegSocial extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const DSS_ESTADO_CON_FICHERO = 'Con Fichero';
    const DSS_ESTADO_SIN_FICHERO = 'Sin Fichero';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_seg_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dss_descripcion', 'dss_afi_alta', 'dss_afi_baja'], 'default', 'value' => null],
            [['dss_eliminado'], 'default', 'value' => 0],
            [['dss_fecha', 'dss_regimen', 'dss_ccc', 'dss_tipo', 'dss_razon_social', 'dss_estado'], 'required'],
            [['dss_fecha'], 'safe'],
            [['dss_estado'], 'string'],
            [['dss_doc_alta', 'dss_doc_baja', 'dss_eliminado'], 'integer'],
            [['dss_descripcion', 'dss_razon_social', 'dss_afi_alta', 'dss_afi_baja'], 'string', 'max' => 255],
            [['dss_regimen'], 'string', 'max' => 4],
            [['dss_ccc'], 'string', 'max' => 12],
            [['dss_tipo'], 'string', 'max' => 1],
            ['dss_estado', 'in', 'range' => array_keys(self::optsDssEstado())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dss_id' => 'Dss ID',
            'dss_fecha' => 'Dss Fecha',
            'dss_descripcion' => 'Dss Descripcion',
            'dss_regimen' => 'Dss Regimen',
            'dss_ccc' => 'Dss Ccc',
            'dss_tipo' => 'Dss Tipo',
            'dss_razon_social' => 'Dss Razon Social',
            'dss_afi_alta' => 'Dss Afi Alta',
            'dss_afi_baja' => 'Dss Afi Baja',
            'dss_estado' => 'Dss Estado',
            'dss_doc_alta' => 'Dss Doc Alta',
            'dss_doc_baja' => 'Dss Doc Baja',
            'dss_eliminado' => 'Dss Eliminado',
        ];
    }

    /**
     * Gets query for [[DetalleDocSegSocials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleDocSegSocials()
    {
        return $this->hasMany(DetalleDocSegSocial::class, ['dss_id' => 'dss_id']);
    }


    /**
     * column dss_estado ENUM value labels
     * @return string[]
     */
    public static function optsDssEstado()
    {
        return [
            self::DSS_ESTADO_CON_FICHERO => 'Con Fichero',
            self::DSS_ESTADO_SIN_FICHERO => 'Sin Fichero',
        ];
    }

    /**
     * @return string
     */
    public function displayDssEstado()
    {
        return self::optsDssEstado()[$this->dss_estado];
    }

    /**
     * @return bool
     */
    public function isDssEstadoConFichero()
    {
        return $this->dss_estado === self::DSS_ESTADO_CON_FICHERO;
    }

    public function setDssEstadoToConFichero()
    {
        $this->dss_estado = self::DSS_ESTADO_CON_FICHERO;
    }

    /**
     * @return bool
     */
    public function isDssEstadoSinFichero()
    {
        return $this->dss_estado === self::DSS_ESTADO_SIN_FICHERO;
    }

    public function setDssEstadoToSinFichero()
    {
        $this->dss_estado = self::DSS_ESTADO_SIN_FICHERO;
    }
}
