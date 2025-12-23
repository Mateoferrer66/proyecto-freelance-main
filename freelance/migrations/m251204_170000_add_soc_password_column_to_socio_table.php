<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%socio}}`.
 */
class m251204_170000_add_soc_password_column_to_socio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%socio}}', 'soc_password', $this->string(255)->notNull()->after('soc_email'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%socio}}', 'soc_password');
    }
}
