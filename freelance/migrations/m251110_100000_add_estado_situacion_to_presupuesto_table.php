<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%presupuesto}}`.
 */
class m251110_100000_add_estado_situacion_to_presupuesto_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%presupuesto}}', 'pre_estado', $this->string()->notNull()->defaultValue('Pendiente'));
        $this->addColumn('{{%presupuesto}}', 'pre_situacion', $this->string()->notNull()->defaultValue('No Reclamada'));
        $this->addColumn('{{%presupuesto}}', 'pre_fecha_situacion', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%presupuesto}}', 'pre_estado');
        $this->dropColumn('{{%presupuesto}}', 'pre_situacion');
        $this->dropColumn('{{%presupuesto}}', 'pre_fecha_situacion');
    }
}
