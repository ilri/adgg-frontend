<?php
/**
 * Author: Fred <mconyango@gmail.com>
 * Date: 2018-03-12
 * Time: 11:51 PM
 */

namespace backend\modules\dashboard\assets;


use backend\assets\AppAsset;
use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'src';
        parent::init();
    }

    public $js = [
        'js/module.js'

    ];
    public $css = [
        'css/dashboard-styles.css'
    ];
    public $depends = [
        AppAsset::class,
    ];
}