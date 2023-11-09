<?php

use yii\db\Migration;

/**
 * Class m231108_231547_createChats
 */
class m231108_231500_createChats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('chats', [
            'id' => $this->primaryKey()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231108_231547_createChats cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231108_231547_createChats cannot be reverted.\n";

        return false;
    }
    */
}
