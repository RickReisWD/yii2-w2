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

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230916_020313_create_empresa cannot be reverted.\n";

        return false;
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
