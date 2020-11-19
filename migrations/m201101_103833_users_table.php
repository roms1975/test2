<?php

use yii\db\Migration;

/**
 * Class m201101_103833_users_table
 */
class m201101_103833_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$tableOptions = null;
        if ('mysql' === $this->db->driverName) {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('users', [
            'id' => 'int(11) unsigned NOT NULL',
            'username' => $this->string(256),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string(255),
            'password_reset_token' => $this->string(255),
            'email' => $this->string(255),
            'status' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201101_103833_users_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201101_103833_users_table cannot be reverted.\n";

        return false;
    }
    */
}
