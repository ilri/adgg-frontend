<?php

namespace backend\modules\help\assets;

use yii\web\AssetBundle;

class PDFAsset extends AssetBundle
{
    public $sourcePath = '@helpModule/assets/src';

    public $css = [
        [
            'css/mpdf.css', 'media' => 'mpdf'
        ],
    ];

    public $js = [
    ];
    public $depends = [
        Asset::class
    ];
}