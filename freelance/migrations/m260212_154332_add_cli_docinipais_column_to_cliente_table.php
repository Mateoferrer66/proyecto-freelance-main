<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%cliente}}`.
 */
class m260212_154332_add_cli_docinipais_column_to_cliente_table extends Migration
{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%cliente}}');
        if (!isset($table->columns['cli_docinipais'])) {
            $this->addColumn('{{%cliente}}', 'cli_docinipais', $this->string(3)->after('tdo_id'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%cliente}}');
        if (isset($table->columns['cli_docinipais'])) {
            $this->dropColumn('{{%cliente}}', 'cli_docinipais');
        }
    }
}
