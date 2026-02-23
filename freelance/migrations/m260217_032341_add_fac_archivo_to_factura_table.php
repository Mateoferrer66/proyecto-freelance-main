<?php

use yii\db\Migration;

class m260217_032341_add_fac_archivo_to_factura_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'factura',
            'fac_archivo',
            $this->string(255)->null()->defaultValue(null)->after('fac_observaciones')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('factura', 'fac_archivo');
    }
}
