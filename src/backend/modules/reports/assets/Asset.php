<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2018-09-27 23:59
 */

namespace backend\modules\reports\assets;


use backend\assets\AppAsset;
use kartik\select2\Select2Asset;
use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@reportsModule/assets/src';

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.51.0/codemirror.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.51.0/mode/sql/sql.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.51.0/addon/hint/sql-hint.min.js',
        'js/module.js',
    ];

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.51.0/codemirror.min.css',
        'css/report-builder.css'
    ];

    public $depends = [
        Select2Asset::class,
        AppAsset::class,
    ];
}