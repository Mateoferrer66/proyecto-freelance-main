<?php

use yii\db\Migration;

/**
 * Class m251111_020000_alter_soc_id_column_in_cliente_table
 */
class m251111_020000_alter_soc_id_column_in_cliente_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Try to drop FK if it exists (might fail if name is different, but worth a try)
        try {
            $this->dropForeignKey('fk-cliente-soc_id', '{{%cliente}}');
        } catch (\Exception $e) {
            // Ignore if FK doesn't exist or name is different
        }

        $this->alterColumn('{{%cliente}}', 'soc_id', $this->integer()->null());

        // Add FK back
        $this->addForeignKey(
            'fk-cliente-soc_id',
            '{{%cliente}}',
            'soc_id',
            '{{%socio}}',
            'soc_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cliente-soc_id', '{{%cliente}}');
        $this->alterColumn('{{%cliente}}', 'soc_id', $this->integer()->notNull());
        $this->addForeignKey(
            'fk-cliente-soc_id',
            '{{%cliente}}',
            'soc_id',
            '{{%socio}}',
            'soc_id',
            'CASCADE'
        );
    }
}
