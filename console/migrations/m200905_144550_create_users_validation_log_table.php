<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_validation_log}}`.
 */
class m200905_144550_create_users_validation_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_validation_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'validation_error' => $this->string()->notNull(),

            'status' => $this->string()->notNull()->defaultValue('active'),

            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users_validation_log}}');
    }
}
