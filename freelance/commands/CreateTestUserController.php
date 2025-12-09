<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Socio;

/**
 * Controlador para crear usuario de prueba
 */
class CreateTestUserController extends Controller
{
    /**
     * Crea un usuario de prueba con contraseña
     */
    public function actionIndex()
    {
        // Buscar si existe un socio con email de prueba
        $socio = Socio::findOne(['soc_email' => 'test@test.com']);
        
        if (!$socio) {
            $this->stdout("Buscando socio existente para actualizar...\n");
            // Buscar cualquier socio activo
            $socio = Socio::findOne(['soc_estado' => Socio::SOC_ESTADO_ACTIVO, 'soc_eliminado' => 0]);
        }
        
        if ($socio) {
            // Actualizar contraseña del socio existente
            $socio->soc_password = 'test123';
            if ($socio->save(false)) {
                $this->stdout("Usuario actualizado exitosamente!\n");
                $this->stdout("Email: " . $socio->soc_email . "\n");
                $this->stdout("Contraseña: test123\n");
            } else {
                $this->stderr("Error al actualizar el usuario\n");
                print_r($socio->errors);
            }
        } else {
            $this->stdout("No se encontró ningún socio activo.\n");
            $this->stdout("Creando nuevo socio de prueba...\n");
            
            // Crear nuevo socio
            $socio = new Socio();
            $socio->soc_numero = 9999;
            $socio->soc_fecha = date('Y-m-d');
            $socio->soc_nombre = 'Usuario';
            $socio->soc_apellido = 'Prueba';
            $socio->soc_apellido1 = 'Prueba';
            $socio->tdo_id = 1;
            $socio->soc_numdocide = '12345678A';
            $socio->soc_fecnacimiento = '1990-01-01';
            $socio->soc_sexo = Socio::SOC_SEXO_MASCULINO;
            $socio->soc_numsegsocial = '123456789012';
            $socio->soc_ctabancaria = 'ES1234567890123456789012';
            $socio->soc_email = 'test@test.com';
            $socio->soc_password = 'test123';
            $socio->soc_estado = Socio::SOC_ESTADO_ACTIVO;
            $socio->soc_eliminado = 0;
            $socio->soc_participacion_desde = 0;
            $socio->soc_participacion_hasta = 0;
            $socio->soc_pago_participacion = 0;
            $socio->soc_exportado = 0;
            
            if ($socio->save()) {
                $this->stdout("Usuario creado exitosamente!\n");
                $this->stdout("Email: test@test.com\n");
                $this->stdout("Contraseña: test123\n");
            } else {
                $this->stderr("Error al crear el usuario\n");
                print_r($socio->errors);
            }
        }
    }
    
    /**
     * Lista todos los socios activos con sus emails
     */
    public function actionList()
    {
        $socios = Socio::find()
            ->where(['soc_estado' => Socio::SOC_ESTADO_ACTIVO, 'soc_eliminado' => 0])
            ->all();
            
        $this->stdout("Socios activos:\n");
        foreach ($socios as $socio) {
            $this->stdout("ID: {$socio->soc_id} | Email: {$socio->soc_email} | Nombre: {$socio->soc_nombre} {$socio->soc_apellido1}\n");
        }
        
        if (empty($socios)) {
            $this->stdout("No hay socios activos.\n");
        }
    }
    
    /**
     * Establece contraseña para un socio específico
     * @param int $socioId ID del socio
     * @param string $password Nueva contraseña
     */
    public function actionSetPassword($socioId, $password = 'test123')
    {
        $socio = Socio::findOne($socioId);
        
        if (!$socio) {
            $this->stderr("Socio con ID {$socioId} no encontrado.\n");
            return;
        }
        
        $socio->soc_password = $password;
        if ($socio->save(false)) {
            $this->stdout("Contraseña actualizada para: {$socio->soc_email}\n");
            $this->stdout("Nueva contraseña: {$password}\n");
        } else {
            $this->stderr("Error al actualizar contraseña\n");
        }
    }
}
