<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%socio}}`.
 */
class m260222_050424_add_soc_foto_column_to_socio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%socio}}', 
            'soc_foto', 
            $this->string(255)
                ->null()
                ->after('soc_deuda')
                ->comment('Foto del socio')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%socio}}', 'soc_foto');
    }
}
