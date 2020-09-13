<?php

use yii\db\Migration;

/**
 * Class m200809_090438_create_users_talble
 */
class m200809_090438_create_users_talble extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'phone_number' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'ip_address' => $this->string(15),
            'avatar' => $this->string(),

            'status' => $this->string()->notNull()->defaultValue('active'),

            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),

            
        ]);

        $this->createIndex(
            'index',
            'users',
            ['phone_number',
            'status',
            'created_at']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200809_090438_create_users_talble cannot be reverted.\n";

        return false;
    }
    */
}
