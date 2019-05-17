<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-12-19
 * Time: 15:20
 */

namespace backend\modules\accounting\assets;

use backend\assets\AppAsset;
use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@accountingModule/assets/src';

    public $js = [
        'js/module.js',
    ];

    public $depends = [
        AppAsset::class,
    ];
}