<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-11-23 15:30
 * Time: 15:30
 */

namespace backend\assets;


use Yii;
use yii\web\AssetBundle;

class ModulesAsset extends AssetBundle
{
    public function init()
    {
        $moduleId = Yii::$app->controller->module->getUniqueId();

        $depends = [
            AppAsset::class,
        ];
        if ($moduleId === 'auth') {
            $depends[] = \backend\modules\auth\assets\Asset::class;
        } elseif ($moduleId === 'conf') {
            $depends[] = \backend\modules\conf\assets\Asset::class;
        } elseif ($moduleId === 'dashboard') {
            $depends[] = \backend\modules\dashboard\assets\Asset::class;
        } elseif ($moduleId === 'reports') {
            $depends[] = \backend\modules\reports\assets\Asset::class;
        } elseif ($moduleId === 'core') {
            $depends[] = \backend\modules\core\assets\Asset::class;
        } elseif ($moduleId === 'help') {
            $depends[] = \backend\modules\help\assets\Asset::class;
        } elseif ($moduleId === 'accounting') {
            $depends[] = \backend\modules\accounting\assets\Asset::class;
        }

        $this->depends = $depends;

        parent::init();
    }
}