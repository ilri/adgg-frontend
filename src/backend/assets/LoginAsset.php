<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-26
 * Time: 2:13 PM
 */

namespace backend\assets;


use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $sourcePath = '@backendAssets/assets';

    public $css = [
        'css/override-login.css',
    ];

    public $depends = [
        AppAsset::class,
    ];
}