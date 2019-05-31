<?php

use backend\modules\core\models\Organization;
use common\widgets\grid\GridView;
use yii\bootstrap4\Html;

/* @var $this \yii\web\View */
/* @var $model Organization */
?>
<?= GridView::widget([
    'searchModel' => $model,
    'createButton' => ['visible' => Yii::$app->user->canCreate(), 'modal' => false],
    'columns' => [
        [
            'attribute' => 'account_no',
        ],
        [
            'attribute' => 'name',
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
            'attribute' => 'contact_alt_phone',
        ],
        [
            'attribute' => 'contact_email',
            'format' => 'email',
        ],
        [
            'attribute' => 'street',
        ],
        [
            'attribute' => 'postal_address',
        ],
        [
            'class' => common\widgets\grid\ActionColumn::class,
            'template' => '{update}{delete}',
            'visibleButtons' => [
                'update' => function (Organization $model) {
                    return Yii::$app->user->canUpdate();
                }
            ],
            'updateOptions' => ['data-pjax' => 0, 'title' => 'Update', 'modal' => false,'data-use-uuid'=>true],
        ],
    ],
]);
?>