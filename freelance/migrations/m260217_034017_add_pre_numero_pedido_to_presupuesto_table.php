<?php

use yii\db\Migration;

class m260217_034017_add_pre_numero_pedido_to_presupuesto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'presupuesto',
            'pre_numero_pedido',
            $this->string(45)->null()->defaultValue(null)->after('pre_numero')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('presupuesto', 'pre_numero_pedido');
    }
}