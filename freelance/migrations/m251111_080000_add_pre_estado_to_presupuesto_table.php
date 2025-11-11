<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%presupuesto}}`.
 */
class m251111_080000_add_pre_estado_to_presupuesto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%presupuesto}}', 'pre_estado', $this->string(255)->notNull()->defaultValue('Pendiente'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%presupuesto}}', 'pre_estado');
    }
}
