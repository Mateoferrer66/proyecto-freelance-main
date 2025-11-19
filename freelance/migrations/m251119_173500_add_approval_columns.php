<?php

use yii\db\Migration;

/**
 * Class m251119_173500_add_approval_columns
 */
class m251119_173500_add_approval_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('factura', 'fac_aprobada', $this->integer()->defaultValue(0)->comment('0: No aprobada, 1: Aprobada'));
        $this->addColumn('presupuesto', 'pre_aprobado', $this->integer()->defaultValue(0)->comment('0: No aprobado, 1: Aprobado'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('factura', 'fac_aprobada');
        $this->dropColumn('presupuesto', 'pre_aprobado');
    }
}
