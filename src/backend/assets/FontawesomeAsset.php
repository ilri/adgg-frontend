<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-18
 * Time: 10:43 PM
 */

namespace backend\assets;


use yii\web\AssetBundle;

class FontawesomeAsset extends AssetBundle
{
    public $sourcePath = '@backendAssets/assets/fonts/fontawesome';
    public $css = [
        'css/all.min.css',
    ];
}