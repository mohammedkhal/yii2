<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_files}}`.
 */
class m200823_192720_create_users_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_files}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'file' => $this->string(),

            'status' => $this->string()->notNull()->defaultValue('active'),

            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),

            
        ]);

        $this->addForeignKey(
            '{{%fk-users_files-user_id}}',
            '{{%users_files}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_files}}');
    }
}
