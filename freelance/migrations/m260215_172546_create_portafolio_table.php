<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%portafolio}}`.
 */
class m260215_172546_create_portafolio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%portafolio}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%portafolio}}');
    }
}
