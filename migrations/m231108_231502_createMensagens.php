<?php

use yii\db\Migration;

/**
 * Class m231108_231502_createMensagens
 */
class m231108_231502_createMensagens extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('mensagens',[
            'id' => $this->primaryKey(),
            'mensagem' => $this->string(500),
            'data' => $this->dateTime(),
            'pessoa_id' => $this->integer()->notNull(),
            'chat_id' => $this->integer()->notNull()
        ]);

        $this->createIndex(
            'idx-mensagens-pessoa_id',
            'mensagens',
            'pessoa_id'
        );

       
        $this->addForeignKey(
            'fk-mensagens-pessoa_id',
            'mensagens',
            'pessoa_id',
            'pessoas',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-mensagens-chat_id',
            'mensagens',
            'chat_id'
        );

       
        $this->addForeignKey(
            'fk-mensagens-chat_id',
            'mensagens',
            'chat_id',
            'chats',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231108_231502_createMensagens cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231108_231502_createMensagens cannot be reverted.\n";

        return false;
    }
    */
}
