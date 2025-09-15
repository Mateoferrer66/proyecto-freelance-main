<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empresa".
 *
 * @property int $emp_id Código identificador del registro
 * @property string $emp_razon_social Nombre de la empresa
 * @property int $tdo_id Tipo documento identidad
 * @property string $emp_numdocide Número CIF/NIF
 * @property string $emp_direccion Dirección de la empresa
 * @property string $emp_codpostal
 * @property string|null $emp_poblacion Población
 * @property string $emp_telefono Teléfono de la empresa
 * @property string|null $emp_fax Fax de la empresa
 * @property string $emp_email Correo electrónico de la empresa
 * @property string $emp_regimen_segs Régimen de la seguridad social
 * @property string $emp_ccc_segs Código de Cuenta de Cotización (CCC)
 * @property string $emp_tipo_segs Tipo: código de 1 carácter que identifica el tipo de empresa
 * @property string $emp_razons_segs Nombre de la empresa o entidad
 * @property int $emp_participaciones Cantidad de participaciones que se asigna a cada socio
 *
 * @property TipoDocIdentidad $tdo
 */
class Empresa extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_poblacion', 'emp_fax'], 'default', 'value' => null],
            [['emp_participaciones'], 'default', 'value' => 80],
            [['emp_razon_social', 'tdo_id', 'emp_numdocide', 'emp_direccion', 'emp_codpostal', 'emp_telefono', 'emp_email', 'emp_regimen_segs', 'emp_ccc_segs', 'emp_tipo_segs', 'emp_razons_segs'], 'required'],
            [['tdo_id', 'emp_participaciones'], 'integer'],
            [['emp_razon_social', 'emp_direccion', 'emp_poblacion', 'emp_email', 'emp_razons_segs'], 'string', 'max' => 255],
            [['emp_numdocide'], 'string', 'max' => 20],
            [['emp_codpostal'], 'string', 'max' => 10],
            [['emp_telefono', 'emp_fax'], 'string', 'max' => 45],
            [['emp_regimen_segs'], 'string', 'max' => 4],
            [['emp_ccc_segs'], 'string', 'max' => 12],
            [['emp_tipo_segs'], 'string', 'max' => 1],
            [['tdo_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoDocIdentidad::class, 'targetAttribute' => ['tdo_id' => 'tdo_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'emp_id' => 'Emp ID',
            'emp_razon_social' => 'Emp Razon Social',
            'tdo_id' => 'Tdo ID',
            'emp_numdocide' => 'Emp Numdocide',
            'emp_direccion' => 'Emp Direccion',
            'emp_codpostal' => 'Emp Codpostal',
            'emp_poblacion' => 'Emp Poblacion',
            'emp_telefono' => 'Emp Telefono',
            'emp_fax' => 'Emp Fax',
            'emp_email' => 'Emp Email',
            'emp_regimen_segs' => 'Emp Regimen Segs',
            'emp_ccc_segs' => 'Emp Ccc Segs',
            'emp_tipo_segs' => 'Emp Tipo Segs',
            'emp_razons_segs' => 'Emp Razons Segs',
            'emp_participaciones' => 'Emp Participaciones',
        ];
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

}
