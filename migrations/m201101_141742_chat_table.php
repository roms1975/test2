<?php

use yii\db\Migration;

/**
 * Class m201101_141742_chat_table
 */
class m201101_141742_chat_table extends Migration
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
	
        $this->createTable('chat', [
            'id' => $this->primaryKey(),
            'user' => $this->integer(),
            'message' => $this->text(),
            'correct' => $this->boolean(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('chat');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201101_141742_chat_table cannot be reverted.\n";

        return false;
    }
    */
}
