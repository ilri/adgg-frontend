<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2018-09-27 23:59
 */

namespace backend\modules\reports\assets;


use backend\assets\AppAsset;
use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@reportsModule/assets/src';

    public $js = [
        'js/module.js',
    ];

    public $css = [
    ];

    public $depends = [
        AppAsset::class,
    ];
}