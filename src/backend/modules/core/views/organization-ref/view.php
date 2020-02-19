<?php

use backend\modules\core\models\OrganizationRef;
use backend\modules\core\models\OrganizationRefUnits;
use common\helpers\Lang;
use common\helpers\Utils;
use yii\bootstrap\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model OrganizationRef */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-12">
        <?= $this->render('_tab', ['model' => $model]); ?>
        <div class="tab-content">
            <div class="btn-toolbar">
                <?php if (Yii::$app->user->canCreate()): ?>
                    <div class="btn-group">
                        <a class="btn btn-secondary btn-space"
                           href="<?= Url::to(['organization-ref-units/upload', 'country_id' => $model->id, 'level' => OrganizationRefUnits::LEVEL_REGION]) ?>">
                            <i class="fas fa-file-excel"></i> <?= Lang::t('Upload {units}', ['units' => Inflector::pluralize(Html::encode($model->unit1_name))]) ?>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-secondary btn-space"
                           href="<?= Url::to(['organization-ref-units/upload', 'country_id' => $model->id, 'level' => OrganizationRefUnits::LEVEL_DISTRICT]) ?>">
                            <i class="fas fa-file-excel"></i> <?= Lang::t('Upload {units}', ['units' => Inflector::pluralize(Html::encode($model->unit2_name))]) ?>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-secondary btn-space"
                           href="<?= Url::to(['organization-ref-units/upload', 'country_id' => $model->id, 'level' => OrganizationRefUnits::LEVEL_WARD]) ?>">
                            <i class="fas fa-file-excel"></i> <?= Lang::t('Upload {units}', ['units' => Inflector::pluralize(Html::encode($model->unit3_name))]) ?>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-secondary"
                           href="<?= Url::to(['organization-ref-units/upload', 'country_id' => $model->id, 'level' => OrganizationRefUnits::LEVEL_VILLAGE]) ?>">
                            <i class="fas fa-file-excel"></i> <?= Lang::t('Upload {units}', ['units' => Inflector::pluralize(Html::encode($model->unit4_name))]) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <br>
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table detail-view table-striped'],
                'attributes' => [
                    [
                        'attribute' => 'name',
                    ],
                    [
                        'attribute' => 'dialing_code',
                    ],
                    [
                        'attribute' => 'code',
                        'visible' => true,
                    ],
                    [
                        'attribute' => 'contact_person',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'contact_phone',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'contact_email',
                        'format' => 'email',
                        'visible' => false,
                    ],
                    [
                        'attribute' => 'is_active',
                        'value' => Utils::decodeBoolean($model->is_active),
                    ],
                    [
                        'attribute' => 'unit1_name',
                    ],
                    [
                        'attribute' => 'unit2_name',
                    ],
                    [
                        'attribute' => 'unit3_name',
                    ],
                    [
                        'attribute' => 'unit4_name',
                    ],
                    [
                        'attribute' => 'uuid',
                        'visible' => false,
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
