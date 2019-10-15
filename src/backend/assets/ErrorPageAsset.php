<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-09-10
 * Time: 2:55 PM
 */

namespace backend\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

class ErrorPageAsset extends AssetBundle
{
    public $sourcePath = '@backendAssets/assets';

    public $css=[
        'css/error-page.css',
    ];
    public $depends = [
        FontAsset::class,
        JqueryAsset::class,
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        BowerAsset::class,
        NPMAsset::class,
        FontawesomeAsset::class,
        //ThemeAsset::class,
    ];
}