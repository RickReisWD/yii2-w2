<?php

use yii\db\Migration;

/**
 * Class m230916_020313_create_empresa
 */
class m230916_020313_create_empresa extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('companies',[
            'id'=> $this->primaryKey(),
            'name'=>$this->string(255)->notNull(),
            'legal_name'=>$this->string(255)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('companies');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230916_020313_create_empresa cannot be reverted.\n";

        return false;
    }
    */
}
