<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%socio}}`.
 */
class m260222_060004_add_soc_perfil_column_to_socio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%socio}}', 
            'soc_perfil', 
            $this->text()
                ->null()
                ->comment('Perfil detallado del socio')
                ->after('soc_foto')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%socio}}', 'soc_perfil');
    }
}
