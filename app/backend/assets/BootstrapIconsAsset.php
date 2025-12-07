<?php

namespace backend\assets;

use yii\web\AssetBundle;

class BootstrapIconsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/twbs/bootstrap-icons';
    public $css = [
        'font/bootstrap-icons.css',
    ];
}