<?php

use backend\modules\core\models\RegistrationDocument;
use backend\modules\core\models\RegistrationDocumentType;
use common\helpers\DateUtils;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\bootstrap4\Html;

/* @var $this \yii\web\View */
/* @var $model RegistrationDocument */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'showExportButton' => true,
    'columns' => [
        [
            'class' => \kartik\grid\ExpandRowColumn::class,
            'width' => '50px',
            'value' => function () {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function (RegistrationDocument $model) {
                return Yii::$app->controller->renderPartial('_view', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true,
            'enableRowClick' => false,
            'collapseIcon' => '<i class="fas fa-chevron-down"></i>',
            'expandIcon' => '<i class="fas fa-chevron-right"></i>',
            'format' => 'raw',
        ],
        [
            'attribute' => 'document_no',
        ],
        [
            'attribute' => 'doc_type_id',
            'value' => function (RegistrationDocument $model) {
                return $model->getRelationAttributeValue('docType', 'name');
            },
            'filter' => RegistrationDocumentType::getOrgListData($model->org->business_type,$model->org->business_entity_type),
        ],
        [
            'attribute' => 'description',
            'filter' => false,
        ],
        [
            'attribute' => 'start_date',
            'value' => function (RegistrationDocument $model) {
                return DateUtils::formatDate($model->start_date, 'd-M-Y');
            },
            'hidden' => true,
            'filter' => false,
        ],
        [
            'attribute' => 'renewal_date',
            'value' => function (RegistrationDocument $model) {
                return DateUtils::formatDate($model->renewal_date, 'd-M-Y');
            },
            'hidden' => true,
            'filter' => false,
        ],
        [
            'attribute' => 'description',
            'filter' => false,
        ],
        [
            'attribute' => 'is_approved',
            'value' => function (RegistrationDocument $model) {
                return Html::tag('span', Utils::decodeBoolean($model->is_approved), ['class' => $model->is_approved ? 'badge badge-success' : 'badge badge-secondary']);
            },
            'format' => 'raw',
            'filter' => Utils::booleanOptions(),
        ],
        [
            'attribute' => 'is_active',
            'value' => function (RegistrationDocument $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{approve}{update}{delete}',
            'visibleButtons' => [
                'update' => function (RegistrationDocument $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'width' => '200px',
            'buttons' => [
                'approve' => function ($url, RegistrationDocument $model) {
                    return Yii::$app->user->canUpdate() && $model->canBeApproved() ? Html::a('<i class="fas fa-check"></i> Approve', $url, ['data-toggle' => 'modal', 'data-pjax' => 0, 'data-grid' => $model->getPjaxWidgetId()]) : "";
                }
            ],
        ],
    ],
]);
?>