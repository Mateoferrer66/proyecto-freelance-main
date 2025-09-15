<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property int $usu_id Código identificador del registro
 * @property string $usu_nombre Nombre del usuario
 * @property string|null $usu_apellido Apellido del usuario
 * @property string $usu_email Correo electrónico del usuario
 * @property string $usu_login Login del usuario
 * @property string $usu_password Password del usuario
 * @property string $usu_estado Estado del usuario: Activo, Inactivo
 * @property string|null $usu_fecbloqueo Fecha de bloqueo del usuario
 * @property int $usu_eliminado Campo que indica si el usuario se encuentra eliminado: 1 - Si, 0 - No
 *
 * @property CliAltaBaja[] $cliAltaBajas
 * @property Liquidacion[] $liquidacions
 * @property SocAltaBaja[] $socAltaBajas
 */
class Usuario extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const USU_ESTADO_ACTIVO = 'Activo';
    const USU_ESTADO_INACTIVO = 'Inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usu_apellido', 'usu_fecbloqueo'], 'default', 'value' => null],
            [['usu_estado'], 'default', 'value' => 'Activo'],
            [['usu_eliminado'], 'default', 'value' => 0],
            [['usu_nombre', 'usu_email', 'usu_login', 'usu_password'], 'required'],
            [['usu_estado'], 'string'],
            [['usu_fecbloqueo'], 'safe'],
            [['usu_eliminado'], 'integer'],
            [['usu_nombre', 'usu_apellido', 'usu_email', 'usu_login', 'usu_password'], 'string', 'max' => 255],
            ['usu_estado', 'in', 'range' => array_keys(self::optsUsuEstado())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usu_id' => 'Usu ID',
            'usu_nombre' => 'Usu Nombre',
            'usu_apellido' => 'Usu Apellido',
            'usu_email' => 'Usu Email',
            'usu_login' => 'Usu Login',
            'usu_password' => 'Usu Password',
            'usu_estado' => 'Usu Estado',
            'usu_fecbloqueo' => 'Usu Fecbloqueo',
            'usu_eliminado' => 'Usu Eliminado',
        ];
    }

    /**
     * Gets query for [[CliAltaBajas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCliAltaBajas()
    {
        return $this->hasMany(CliAltaBaja::class, ['usu_id' => 'usu_id']);
    }

    /**
     * Gets query for [[Liquidacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidacions()
    {
        return $this->hasMany(Liquidacion::class, ['usu_id' => 'usu_id']);
    }

    /**
     * Gets query for [[SocAltaBajas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSocAltaBajas()
    {
        return $this->hasMany(SocAltaBaja::class, ['usu_id' => 'usu_id']);
    }


    /**
     * column usu_estado ENUM value labels
     * @return string[]
     */
    public static function optsUsuEstado()
    {
        return [
            self::USU_ESTADO_ACTIVO => 'Activo',
            self::USU_ESTADO_INACTIVO => 'Inactivo',
        ];
    }

    /**
     * @return string
     */
    public function displayUsuEstado()
    {
        return self::optsUsuEstado()[$this->usu_estado];
    }

    /**
     * @return bool
     */
    public function isUsuEstadoActivo()
    {
        return $this->usu_estado === self::USU_ESTADO_ACTIVO;
    }

    public function setUsuEstadoToActivo()
    {
        $this->usu_estado = self::USU_ESTADO_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isUsuEstadoInactivo()
    {
        return $this->usu_estado === self::USU_ESTADO_INACTIVO;
    }

    public function setUsuEstadoToInactivo()
    {
        $this->usu_estado = self::USU_ESTADO_INACTIVO;
    }
}
