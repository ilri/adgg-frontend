<?php

use backend\modules\core\models\FarmMetadataType;
use common\helpers\Lang;
use yii\helpers\Url;

/* @var $controller backend\controllers\BackendController */
$controller = Yii::$app->controller;
$type = Yii::$app->request->get('type', null);
$country_id = Yii::$app->request->get('country_id', null);
?>
<?php
$childType= FarmMetadataType::findOne(['code'=>$type]);
$parent_id= $childType->parent_id;
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= empty($type) ? ' active' : '' ?>"
           href="<?= Url::to(['upload', 'country_id' => $country_id]) ?>">
            <?= Lang::t('Upload Farms') ?>
        </a>
    </li>
    <?php foreach (FarmMetadataType::getListData('code', 'name', false, ['parent_id' => null]) as $value => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= (($type == $value) || $value == $parent_id) ? ' active' : '' ?>"
               href="<?= Url::to(['upload-metadata', 'type' => $value, 'country_id' => $country_id]) ?>">
                <?= Lang::t('Upload {metadataType}', ['metadataType' => $label]) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>