<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_doc_seg_social".
 *
 * @property int $dtdss_id Código identificador del registro
 * @property int $dss_id Código del documento de alta/baja seguridad social
 * @property int $soc_id Código del socio
 * @property string $dtdss_naf Número de afiliación a la Seguridad Social (NAF)
 * @property string $dtdss_ipf Identificador de la Persona Física (IPF)
 * @property string $dtdss_nombre Nombre del trabajador
 * @property string $dtdss_apellidos Apellidos del trabajador
 * @property int|null $dtdss_sexo Sexo del Trabajador (1-Hombre / 2-Mujer)
 * @property string|null $dtdss_telefono Número de teléfono móvil al que enviar notificación de las altas y bajas realizadas con ese NSS
 * @property string $dtdss_situacion Situación laboral del trabajador, ya sea alta o baja
 * @property string $dtdss_fecha_situacion Fecha en que se realiza el alta o baja del trabajador
 * @property string $dtdss_gc
 * @property string|null $dtdss_coeficiente Coeficiente de la jornada realizada por el trabajador en relación con la jornada habitual de la empresa
 * @property int $dtdss_rel_register Código de registro relacionado, si la situación es de alta acá va el código del registro de la situación de baja (si está en el mismo documento)
 *
 * @property DocumentoSegSocial $dss
 * @property RelSocCotizDetSegSocial $relSocCotizDetSegSocial
 * @property Socio $soc
 */
class DetalleDocSegSocial extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const DTDSS_SITUACION_ALTA = 'Alta';
    const DTDSS_SITUACION_BAJA = 'Baja';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_doc_seg_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dtdss_sexo', 'dtdss_telefono', 'dtdss_coeficiente'], 'default', 'value' => null],
            [['dtdss_gc'], 'default', 'value' => 8],
            [['dtdss_rel_register'], 'default', 'value' => 0],
            [['dss_id', 'soc_id', 'dtdss_naf', 'dtdss_ipf', 'dtdss_nombre', 'dtdss_apellidos', 'dtdss_situacion', 'dtdss_fecha_situacion'], 'required'],
            [['dss_id', 'soc_id', 'dtdss_sexo', 'dtdss_rel_register'], 'integer'],
            [['dtdss_situacion'], 'string'],
            [['dtdss_fecha_situacion'], 'safe'],
            [['dtdss_naf'], 'string', 'max' => 12],
            [['dtdss_ipf'], 'string', 'max' => 18],
            [['dtdss_nombre', 'dtdss_apellidos'], 'string', 'max' => 255],
            [['dtdss_telefono'], 'string', 'max' => 45],
            [['dtdss_gc'], 'string', 'max' => 2],
            [['dtdss_coeficiente'], 'string', 'max' => 3],
            ['dtdss_situacion', 'in', 'range' => array_keys(self::optsDtdssSituacion())],
            [['dss_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentoSegSocial::class, 'targetAttribute' => ['dss_id' => 'dss_id']],
            [['soc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socio::class, 'targetAttribute' => ['soc_id' => 'soc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dtdss_id' => 'Dtdss ID',
            'dss_id' => 'Dss ID',
            'soc_id' => 'Soc ID',
            'dtdss_naf' => 'Dtdss Naf',
            'dtdss_ipf' => 'Dtdss Ipf',
            'dtdss_nombre' => 'Dtdss Nombre',
            'dtdss_apellidos' => 'Dtdss Apellidos',
            'dtdss_sexo' => 'Dtdss Sexo',
            'dtdss_telefono' => 'Dtdss Telefono',
            'dtdss_situacion' => 'Dtdss Situacion',
            'dtdss_fecha_situacion' => 'Dtdss Fecha Situacion',
            'dtdss_gc' => 'Dtdss Gc',
            'dtdss_coeficiente' => 'Dtdss Coeficiente',
            'dtdss_rel_register' => 'Dtdss Rel Register',
        ];
    }

    /**
     * Gets query for [[Dss]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDss()
    {
        return $this->hasOne(DocumentoSegSocial::class, ['dss_id' => 'dss_id']);
    }

    /**
     * Gets query for [[RelSocCotizDetSegSocial]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelSocCotizDetSegSocial()
    {
        return $this->hasOne(RelSocCotizDetSegSocial::class, ['dtdss_id' => 'dtdss_id']);
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
     * column dtdss_situacion ENUM value labels
     * @return string[]
     */
    public static function optsDtdssSituacion()
    {
        return [
            self::DTDSS_SITUACION_ALTA => 'Alta',
            self::DTDSS_SITUACION_BAJA => 'Baja',
        ];
    }

    /**
     * @return string
     */
    public function displayDtdssSituacion()
    {
        return self::optsDtdssSituacion()[$this->dtdss_situacion];
    }

    /**
     * @return bool
     */
    public function isDtdssSituacionAlta()
    {
        return $this->dtdss_situacion === self::DTDSS_SITUACION_ALTA;
    }

    public function setDtdssSituacionToAlta()
    {
        $this->dtdss_situacion = self::DTDSS_SITUACION_ALTA;
    }

    /**
     * @return bool
     */
    public function isDtdssSituacionBaja()
    {
        return $this->dtdss_situacion === self::DTDSS_SITUACION_BAJA;
    }

    public function setDtdssSituacionToBaja()
    {
        $this->dtdss_situacion = self::DTDSS_SITUACION_BAJA;
    }
}
