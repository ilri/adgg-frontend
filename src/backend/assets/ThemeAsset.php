<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 23/11/18
 * Time: 14:34
 */

namespace backend\assets;


use Yii;
use yii\web\AssetBundle;

// set @themes alias so we do not have to update baseUrl every time we change themes
Yii::setAlias('@themes', Yii::$app->view->theme->basePath);

class ThemeAsset extends AssetBundle
{
    public $sourcePath = '@themes/assets';

    public function init()
    {
        parent::init();
        $this->css = [
            'css/pages/custom/general/user/login-v2.css',
            'vendors/custom/flaticon/flaticon.css',
            'vendors/custom/flaticon/flaticon2.css',
            'vendors/custom/line-awesome/css/line-awesome.css',
            'vendors/custom/flaticon/flaticon.css',
            'vendors/custom/flaticon2/flaticon.css',
            'css/style.bundle.css',
            'css/skins/header/base/light.css',
            'css/skins/header/menu/light.css',
            'css/skins/brand/light.css',
            'css/skins/aside/light.css',
        ];
        $this->js = [
            'js/scripts.bundle.js',
        ];
    }

    public $depends = [
    ];
}