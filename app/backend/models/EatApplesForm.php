<?php

namespace backend\models;

use yii\base\Model;

class EatApplesForm extends Model
{
    public int $amount = 0;

    public function rules(): array
    {
        return [
            [['amount'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return ['amount'=>'Колличество'];
    }
}