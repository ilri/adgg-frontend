<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-10-31 00:24 AM
 */

namespace backend\modules\core\assets;


use backend\assets\AppAsset;
use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@coreModule/assets/src';

    public $js = [
        'js/module.js',
    ];

    public $css = [
        //'css/module.css',
        'css/gatepass-print.css',
        'css/profile.css',
    ];

    public $depends = [
        AppAsset::class,
    ];
}