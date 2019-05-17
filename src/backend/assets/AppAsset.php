<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2018-11-23 14:34
 */

namespace backend\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;


class AppAsset extends AssetBundle
{
    public $sourcePath = '@backendAssets/assets';

    public function init()
    {
        parent::init();

        $this->css = [
            'css/reports.css',
            'css/print.css',
            'css/custom.css',
            'css/overrides.css',
        ];
        $this->js = [
            'js/myapp.js',
            'js/plugins.js',
            'js/script.js',
        ];
    }

    public $depends = [
        JqueryAsset::class,
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        BowerAsset::class,
        NPMAsset::class,
        ThemeAsset::class,
    ];
}
