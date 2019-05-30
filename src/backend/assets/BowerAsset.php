<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class BowerAsset
 * Manage assets pulled in by bower
 *
 * @package common\assets
 */
class BowerAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $css = [
        'jquery-ui/themes/south-street/jquery-ui.min.css',
    ];

    public $js = [
        'jquery-ui/jquery-ui.min.js',
    ];
}