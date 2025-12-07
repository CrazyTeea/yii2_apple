<?php

namespace backend\controllers;


use backend\models\Apple;
use yii\base\Exception;
use yii\web\Controller;

class AppleController extends Controller
{
    /**
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function actionIndex(): false|string
    {
        $apple = new Apple();
        $apple->setColor();
        $apple->save();
        $ret = ['apple_old'=>[...$apple->toArray(), 'size'=>$apple->size]];
        $apple->status = Apple::$STATE_FELLED;

        $apple->eat(25);

        $ret['apple'] = [...$apple->toArray(), 'size'=>$apple->size];
        return json_encode($ret);
    }

}