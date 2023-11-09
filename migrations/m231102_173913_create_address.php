<?php

use yii\db\Migration;

/**
 * Class m231102_173913_create_address
 */
class m231102_173913_create_address extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address',[
            'id'=> $this->primaryKey(),
            'country'=>$this->string(255)->notNull(),
            'uf'=>$this->string(255)->notNull(),
            'city'=>$this->string(255)->notNull(),
            'district'=>$this->string(255)->notNull(),
            'street'=>$this->string(255)->notNull(),
            'number'=>$this->integer()->notNull(),
            'CEP'=>$this->integer()->notNull(),
            'pessoas_id' => $this->integer()->notNull()
        ]);

        $this->createIndex(
            'idx-address-pessoas_id',
            'address',
            'pessoas_id'
        );

       
        $this->addForeignKey(
            'fk-address-pessoas_id',
            'address',
            'pessoas_id',
            'pessoas',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231102_173913_create_address cannot be reverted.\n";

        return false;
    }
    */
}
