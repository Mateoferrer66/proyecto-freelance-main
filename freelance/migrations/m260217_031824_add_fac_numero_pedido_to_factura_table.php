<?php

use yii\db\Migration;

class m260217_031824_add_fac_numero_pedido_to_factura_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'factura',
            'fac_numero_pedido',
            $this->string(45)->null()->defaultValue(null)->after('fac_numero')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('factura', 'fac_numero_pedido');
    }
}