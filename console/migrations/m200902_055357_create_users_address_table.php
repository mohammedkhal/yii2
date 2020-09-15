<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_address}}`.
 */
class m200902_055357_create_users_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users_address}}', [
            'id' => $this->primaryKey(),
            'country' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'resident' => $this->string()->notNull(),
            'national_number' => $this->string(10),
            'passport_number' => $this->string(10),
            'avatar' => $this->string(),

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
        $this->dropTable('{{%users_address}}');
    }
}
