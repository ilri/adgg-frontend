<?php

use backend\modules\core\models\RegistrationDocumentType;
use common\helpers\Utils;
use common\widgets\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model RegistrationDocumentType */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => true],
    'columns' => [
        [
            'attribute' => 'name',
        ],
        [
            'attribute' => 'business_types',
            'value' => function (RegistrationDocumentType $model) {
                return $model->getFormattedBusinessTypes();
            },
            'filter' => false,
        ],
        [
            'attribute' => 'business_entity_types',
            'value' => function (RegistrationDocumentType $model) {
                return $model->getFormattedBusinessEntityTypes();
            },
            'filter' => false,
        ],
        [
            'attribute' => 'has_renewal',
            'value' => function (RegistrationDocumentType $model) {
                return Utils::decodeBoolean($model->has_renewal);
            },
            'filter' => Utils::booleanOptions(),
            'visible' => false,
        ],
        [
            'attribute' => 'is_active',
            'value' => function (RegistrationDocumentType $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions(),
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{delete}',
            'visibleButtons' => [
                'delete' => function () {
                    return \backend\modules\auth\Session::isDev();
                }
            ]
        ],
    ],
]);
?>