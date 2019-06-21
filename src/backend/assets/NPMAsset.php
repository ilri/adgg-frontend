<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-16
 * Time: 11:29 PM
 */

namespace backend\assets;


use yii\web\AssetBundle;

class NPMAsset extends AssetBundle
{
    public $sourcePath = '@npm';

    public $css = [
        'perfect-scrollbar/css/perfect-scrollbar.css',
        'tether/dist/css/tether.min.css',
        'animate.css/animate.min.css',
        'sweetalert2/dist/sweetalert2.min.css',
        'socicon/css/socicon.css',
    ];

    public $js = [
        'highcharts/highcharts.js',
        'highcharts/highcharts-3d.js',
        'highcharts/modules/exporting.js',
        'js-cookie/src/js.cookie.js',
        'moment/min/moment.min.js',
        'tooltip.js/dist/umd/tooltip.min.js',
        'perfect-scrollbar/dist/perfect-scrollbar.js',
        'sticky-js/dist/sticky.min.js',
        'wnumb/wNumb.js',
        'block-ui/jquery.blockUI.js',
        'sweetalert2/dist/sweetalert2.min.js',
    ];
}