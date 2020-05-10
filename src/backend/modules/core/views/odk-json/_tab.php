<?php


use common\helpers\Lang;
use yii\helpers\Url;

/* @var $model \backend\modules\core\models\OdkJsonQueue */
$tab = Yii::$app->request->get('tab', 1);
$countryId = Yii::$app->request->get('country_id', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= $tab == 1 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 1]) ?>">
            <?= Lang::t('Queued') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 2 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 2]) ?>">
            <?= Lang::t('Processed') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 3 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 3]) ?>">
            <?= Lang::t('Processed with errors') ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 4 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 4]) ?>">
            <?= Lang::t('Processed successfully') ?>
        </a>
    </li>
</ul>