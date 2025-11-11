<?php

use yii\db\Migration;

class m251111_015742_add_ban_titular_tarjeta_to_banco_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251111_015742_add_ban_titular_tarjeta_to_banco_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251111_015742_add_ban_titular_tarjeta_to_banco_table cannot be reverted.\n";

        return false;
    }
    */
}
