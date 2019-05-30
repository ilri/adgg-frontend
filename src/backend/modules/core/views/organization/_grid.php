<?php

use backend\modules\auth\Session;
use backend\modules\core\models\Organization;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $model Organization */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'rowOptions' => function (Organization $model) {
        return ["class" => "linkable", "data-href" => Url::to(['view', "id" => $model->id])];
    },
    'columns' => [
        [
            'attribute' => 'account_no',
        ],
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'business_type',
            'value' => function (Organization $model) {
                return $model->getDecodedBusinessType();
            },
        ],
        [
            'attribute' => 'contact_first_name',
            'label' => 'Contact Name',
            'value' => function (Organization $model) {
                return $model->getFullContactName(true);
            }
        ],
        [
            'attribute' => 'contact_phone',
        ],
        [
            'attribute' => 'account_manager_id',
            'value' => function (Organization $model) {
                return $model->getRelationAttributeValue('accountManager', 'name');
            }
        ],
        [
            'attribute' => 'status',
            'value' => function (Organization $model) {
                return $model->getDecodedStatus();
            },
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{approve}{update}{view}',
            'visibleButtons' => [
                'update' => function (Organization $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false,'data-use-uuid'=>true],
            'width' => '200px',
            'buttons' => [
                'approve' => function ($url, Organization $model) {
                    return Yii::$app->user->canUpdate() && $model->canBeApproved() ? Html::a('<i class="fas fa-check"></i> Approve', $url, ['data-toggle' => 'modal', 'data-pjax' => 0, 'data-grid' => $model->getPjaxWidgetId()]) : "";
                }
            ],
        ],
    ],
]);
?>