<?php

use yii\db\Migration;

/**
 * Class m231102_173727_create_pessoas
 */
class m231102_173727_create_pessoas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pessoas',[
            'id'=> $this->primaryKey(),
            'name'=>$this->string(255)->notNull(),
            'email'=>$this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pessoas');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231102_173727_create_pessoas cannot be reverted.\n";

        return false;
    }
    */
}
