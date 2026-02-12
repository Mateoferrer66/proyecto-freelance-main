<?php

use yii\db\Migration;

/**
 * Class m250211_150000_add_banks
 */
class m250211_150000_add_banks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $banks = ['Bankinter', 'CaixaBank'];
        foreach ($banks as $bankName) {
            $exists = (new \yii\db\Query())
                ->from('banco')
                ->where(['ban_nombre' => $bankName])
                ->exists();
            
            if (!$exists) {
                $this->insert('banco', [
                    'ban_nombre' => $bankName,
                    'ban_numcuenta' => 'ES000000000000000000',
                    'ban_titular_tarjeta' => 'Titular',
                    'ban_eliminado' => 0
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $banks = ['Bankinter', 'CaixaBank'];
        foreach ($banks as $bankName) {
            $this->delete('banco', ['ban_nombre' => $bankName]);
        }
    }
}
