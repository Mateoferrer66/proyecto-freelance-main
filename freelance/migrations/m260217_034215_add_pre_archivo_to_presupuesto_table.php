<?php

use yii\db\Migration;

class m260217_034215_add_pre_archivo_to_presupuesto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'presupuesto',
            'pre_archivo',
            $this->string(255)->null()->defaultValue(null)->after('pre_observaciones')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('presupuesto', 'pre_archivo');
    }
}