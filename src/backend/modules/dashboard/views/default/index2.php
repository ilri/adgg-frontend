<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use common\helpers\Lang;
use common\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $graphFilterOptions array */
/* @var $countries \backend\modules\core\models\Organization[] */
$controller = Yii::$app->controller;
$this->title = Lang::t('Dashboard');
$this->params['breadcrumbs'] = [
    $this->title,
];
?>
<div class="row">
    <?php foreach ($countries as $country): ?>
        <div class="col-md-6">
            <div class='card my-2'>
                <h4 class="card-header bg-white border-0"><?= Html::encode($country->name) ?></h4>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h1 class="text-center kt-font-info">
                                <?= Yii::$app->formatter->asDecimal(Farm::getCount(['org_id' => $country->id])) ?>
                            </h1>
                            <h6 class="text-center font-weight-normal"><?= Lang::t('Number Of Farms') ?></h6>
                            <h1 class="text-center kt-font-info">
                                <?= Yii::$app->formatter->asDecimal(Animal::getCount(['org_id' => $country->id])) ?>
                            </h1>
                            <h6 class="text-center font-weight-normal"><?= Lang::t('Number Of Animals') ?></h6>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card-footer bg-white d-flex justify-content-center justify-content-md-end border-0">
                                <a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space"
                                   href="<?= Url::to(['/dashboard/stats/dash', 'org_id' => $country->id]) ?>">
                                    <?= Lang::t('View {country} Dashboard', ['country' => $country->name]) ?>
                                    <i class="far fa-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
