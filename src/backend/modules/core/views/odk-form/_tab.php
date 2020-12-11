<?php


use common\helpers\Lang;
use yii\helpers\Url;
use backend\modules\core\models\OdkForm;

/* @var $model \backend\modules\core\models\OdkForm */
$tab = Yii::$app->request->get('tab', 1);
$countryId = Yii::$app->request->get('country_id', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= $tab == 1 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 1]) ?>">
            <?= Lang::t('Queued') ?>
            <span class="badge badge-secondary badge-pill">
                <?= Yii::$app ->formatter->asDecimal(OdkForm::getCount(!empty($countryId) ? ['country_id'=>$countryId, 'is_processed' => 0] : ['is_processed' => 0])) ?>

            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 2 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 2]) ?>">
            <?= Lang::t('Processed') ?>
            <span class="badge badge-secondary badge-pill">
                <?= Yii::$app ->formatter->asDecimal(OdkForm::getCount(!empty($countryId) ? ['country_id'=>$countryId, 'is_processed' => 1] : ['is_processed' => 1])) ?>

            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 3 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 3]) ?>">
            <?= Lang::t('Processed with errors') ?>
            <span class="badge badge-secondary badge-pill">
                <?= Yii::$app ->formatter->asDecimal(OdkForm::getCount(!empty($countryId) ? ['country_id'=>$countryId, 'is_processed' => 1, 'has_errors' => 1] : ['is_processed' => 1, 'has_errors' => 1])) ?>

            </span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link<?= $tab == 4 ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'country_id' => $countryId, 'tab' => 4]) ?>">
            <?= Lang::t('Processed successfully') ?>
            <span class="badge badge-secondary badge-pill">
                <?= Yii::$app ->formatter->asDecimal(OdkForm::getCount(!empty($countryId) ? ['country_id'=>$countryId, 'is_processed' => 1, 'has_errors' => 0] : ['is_processed' => 1, 'has_errors' => 0])) ?>

            </span>
        </a>
    </li>
</ul>