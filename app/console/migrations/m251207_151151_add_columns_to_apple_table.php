<?php

use backend\models\Apple;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%apple}}`.
 */
class m251207_151151_add_columns_to_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Apple::tableName(), 'rotten_at', $this->dateTime()->null());
        $this->addColumn(Apple::tableName(), 'active', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Apple::tableName(), 'rotten_at');
        $this->dropColumn(Apple::tableName(), 'active');
    }
}
