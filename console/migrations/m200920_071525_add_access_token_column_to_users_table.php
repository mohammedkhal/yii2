<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m200920_071525_add_access_token_column_to_users_table extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'access_token', $this->string()->after('password'));
    }

    public function down()
    {
        $this->dropColumn('users', 'access_token');
    }
}
