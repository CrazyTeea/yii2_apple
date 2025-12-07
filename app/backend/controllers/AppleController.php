<?php

namespace backend\controllers;

use app\models\Apple;
use yii\web\Controller;

class AppleController extends Controller
{
    public function actionIndex(){
        $apple = new Apple();
        $apple->color = 'red';
        $apple->save();
        $ret = ['apple_old'=>[...$apple->toArray(), 'size'=>$apple->size]];
        $apple->status = Apple::$STATE_FELLED;

        $apple->eat(25);

        $ret['apple'] = [...$apple->toArray(), 'size'=>$apple->size];
        return json_encode($ret);
    }

}