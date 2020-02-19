<?php

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\Session;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use backend\modules\auth\models\Users;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model Users */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'toolbarButtons' => [
        Yii::$app->user->canCreate() ? '<a class="btn btn-brand btn-bold btn-upper btn-font-sm btn-space" href="' . Url::to(['upload', 'country_id' => $model->country_id, 'level_id' => UserLevels::LEVEL_DISTRICT]) . '" data-pjax="0"><i class="fa fa-file-excel-o"></i> ' . Lang::t('Upload Enumerators/AITech') . '</a> ' : '',
    ],
    'rowOptions' => function (Users $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->id])];
    },
    'columns' => [
        [
            'attribute' => 'name',
            'filter' => true,
        ],
        [
            'attribute' => 'username',
            'filter' => true,
            'enableSorting' => true,
        ],
        [
            'attribute' => 'email',
            'filter' => true,
        ],
        [
            'attribute' => 'phone',
            'filter' => true,
        ],
        [
            'attribute' => 'level_id',
            'value' => function (Users $model) {
                return $model->getRelationAttributeValue('level', 'name');
            },
            'visible' => !Session::isCountry(),
        ],
        [
            'attribute' => 'country_id',
            'value' => function (Users $model) {
                return $model->getRelationAttributeValue('country', 'name');
            },
            'visible' => !Session::isCountry(),
        ],
        [
            'attribute' => 'role_id',
            'value' => function (Users $model) {
                return $model->getRelationAttributeValue('role', 'name');
            },
        ],
        [
            'attribute' => 'last_login',
            'value' => function (Users $model) {
                return \common\helpers\DateUtils::formatToLocalDate($model->last_login);
            },
            'filter' => false,
        ],
        [
            'attribute' => 'status',
            'filter' => Users::statusOptions(),
            'value' => function (Users $model) {
                return Users::decodeStatus($model->status);
            },
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{view}{update}',
            'visibleButtons' => [
                'update' => function (Users $model) {
                    return Yii::$app->user->canUpdate() && $model->checkPermission(false, true, false, true);
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false],
        ],
    ],
]);
?>