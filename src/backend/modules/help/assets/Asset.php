<?php

namespace backend\modules\help\assets;

use backend\assets\AppAsset;
use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@helpModule/assets/src';

    public $css = [
        'css/module.css',
    ];

    public $js = [
        'js/module.js'
    ];
    public $depends = [
        AppAsset::class
    ];
}