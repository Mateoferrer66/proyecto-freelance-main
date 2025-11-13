<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

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
class Usuario extends \yii\db\ActiveRecord implements IdentityInterface
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
            // Nombre y email siempre requeridos
            [['usu_nombre', 'usu_email'], 'required'],
            // En creación se requiere contraseña; en edición sólo si se provee
            [['usu_password'], 'required', 'on' => ['create']],
            [['usu_estado', 'usu_login'], 'string'],
            [['usu_fecbloqueo'], 'safe'],
            [['usu_eliminado'], 'default', 'value' => 0],
            [['usu_eliminado'], 'integer'],
            [['usu_nombre', 'usu_apellido', 'usu_email', 'usu_login', 'usu_password'], 'string', 'max' => 255],
            ['usu_estado', 'in', 'range' => array_keys(self::optsUsuEstado())],
            // Validación de formato de contraseña sólo si se provee (skipOnEmpty true)
            ['usu_password', 'string', 'min' => 8, 'skipOnEmpty' => true],
            ['usu_password', 'match', 'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
                'message' => 'La contraseña debe tener al menos 8 caracteres e incluir una mayúscula, una minúscula, un número y un carácter especial.',
                'skipOnEmpty' => true,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usu_id' => 'ID',
            'usu_nombre' => 'Nombre *',
            'usu_apellido' => 'Apellido *',
            'usu_email' => 'Email *',
            'usu_login' => 'Login *',
            'usu_password' => 'Password *',
            'usu_estado' => 'Estado',
            'usu_fecbloqueo' => 'Fecbloqueo',
            'usu_eliminado' => 'Eliminado',
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord || $this->isAttributeChanged('usu_password')) {
                $this->usu_password = Yii::$app->getSecurity()->generatePasswordHash($this->usu_password);
            }
            return true;
        }
        return false;
    }

    /* Implementación de IdentityInterface */

    public static function findIdentity($id)
    {
        return static::findOne(['usu_id' => $id, 'usu_estado' => self::USU_ESTADO_ACTIVO, 'usu_eliminado' => 0]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // No se implementa en este caso
        return null;
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // No se usa authKey en este ejemplo
        return null;
    }

    public function validateAuthKey($authKey)
    {
        // No se usa authKey en este ejemplo
        return false;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['usu_login' => $username, 'usu_estado' => self::USU_ESTADO_ACTIVO, 'usu_eliminado' => 0]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->usu_password);        //return $password === $this->usu_password;
    }
}
