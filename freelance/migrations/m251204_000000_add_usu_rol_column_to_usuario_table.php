<?php

use yii\db\Migration;

/**
 * Class m251204_000000_add_usu_rol_column_to_usuario_table
 */
class m251204_000000_add_usu_rol_column_to_usuario_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('usuario', 'usu_rol', $this->string(20)->after('usu_password'));
        
        // Update existing users to have a default role (e.g., Cooperativa) to avoid login issues immediately
        // You might want to adjust this logic if you have a way to distinguish them already, 
        // but for now, defaulting to 'Cooperativa' is safer for the main app.
        $this->update('usuario', ['usu_rol' => 'Cooperativa']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('usuario', 'usu_rol');
    }
}
