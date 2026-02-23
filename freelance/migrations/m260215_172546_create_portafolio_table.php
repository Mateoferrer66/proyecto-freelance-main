<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%portafolio}}`.
 */
class m260215_172546_create_portafolio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('portafolio', [
            'por_id' => $this->primaryKey()->comment('Código identificador del registro'),
            'soc_id' => $this->bigInteger()->notNull()
                ->comment('Código del socio'),
            'por_titulo' => $this->string(255)->notNull()
                ->comment('Título del proyecto'),
            'por_descripcion' => $this->text()->notNull()
                ->comment('Descripción del proyecto'),
            'por_imagenes' => $this->text()->null()
                ->comment('Imágenes del proyecto (rutas separadas por coma)'),
            'por_eliminado' => $this->boolean()->notNull()->defaultValue(0)
                ->comment('Campo para indicar si el registro está eliminado: 0 - No, 1 - Si'),
            'created_at' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->comment('Fecha de creación'),
            'updated_at' => $this->dateTime()
                ->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
                ->comment('Fecha de última actualización'),
        ],
        'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        // Índice
        $this->createIndex(
            'fk_portafolio_socio',
            'portafolio',
            'soc_id'
        );

        // Foreign Key
        $this->addForeignKey(
            'fk_portafolio_socio',
            'portafolio',
            'soc_id',
            'socio',
            'soc_id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_portafolio_socio', 'portafolio');
        $this->dropIndex('fk_portafolio_socio', 'portafolio');
        $this->dropTable('portafolio');
    }
}
