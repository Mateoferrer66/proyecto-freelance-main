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
        $this->alterColumn('{{%cliente}}', 'soc_id', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%cliente}}', 'soc_id', $this->integer()->notNull());
    }
}
