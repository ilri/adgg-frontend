<?php

use backend\modules\core\models\Organization;
use common\helpers\Utils;
use yii\bootstrap\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

/* @var $this \yii\web\View */
/* @var $model Organization */
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
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table detail-view table-striped'],
                'attributes' => [
                    [
                        'attribute' => 'name',
                    ],
                    [
                        'attribute' => 'code',
                        'visible'=>false,
                    ],
                    [
                        'attribute' => 'contact_person',
                    ],
                    [
                        'attribute' => 'contact_phone',
                    ],
                    [
                        'attribute' => 'contact_email',
                        'format' => 'email',
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
                        'visible'=>false,
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
