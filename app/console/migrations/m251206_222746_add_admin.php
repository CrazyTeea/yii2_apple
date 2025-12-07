<?php

use common\models\User;
use yii\db\Migration;

class m251206_222746_add_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@admin.ru';
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->save();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251206_222746_add_admin cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251206_222746_add_admin cannot be reverted.\n";

        return false;
    }
    */
}
