<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2018-11-23 14:34
 */

namespace backend\assets;

use backend\modules\conf\settings\SystemSettings;
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

        $theme = SystemSettings::getDefaultTheme();
        $this->css = [
            'css/reports.css',
            'css/print.css',
            'css/custom.css',
            'css/modal.css',
            'css/overrides.css',
            'css/overrides-form.css',
            'css/overrides-table.css',
        ];
        if ($theme == SystemSettings::THEME_DEFAULT) {
            $this->css[] = 'css/theme-light.css';
        } elseif ($theme == SystemSettings::THEME_GREEN) {
            //$this->css[] = 'css/theme-green.css';
        } elseif ($theme == SystemSettings::THEME_DARK) {
            //$this->css[] = 'css/theme-dark.css';
        }
        $this->js = [
            'js/utils.js',
            'js/myapp.js',
            'js/plugins.js',
            'js/script.js',
        ];
    }

    public $depends = [
        FontAsset::class,
        JqueryAsset::class,
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        BowerAsset::class,
        NPMAsset::class,
        ThemeAsset::class,
    ];
}
