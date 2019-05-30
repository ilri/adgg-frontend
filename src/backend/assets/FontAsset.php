<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-22
 * Time: 5:39 PM
 */

namespace backend\assets;


use Yii;
use yii\web\AssetBundle;

Yii::setAlias('@themes', Yii::$app->view->theme->basePath);

class FontAsset extends AssetBundle
{
    public $sourcePath = '@themes/fonts';

    public $css = [
        'font.css',
    ];
}