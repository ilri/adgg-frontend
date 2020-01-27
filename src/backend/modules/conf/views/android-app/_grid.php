<?php
use backend\modules\conf\models\AndroidApps;
use common\helpers\Lang;
use common\widgets\grid\GridView;
use common\helpers\Utils;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $user \common\components\User */
/* @var $model AndroidApps */
$user = Yii::$app->user;
?>
<?= GridView::widget([
    'searchModel' => $model,
    'filterModel' => $model,
    'createButton' => ['visible' => $user->canCreate(), 'modal' => true],
    'refreshUrl'=> array_merge(['index'],Yii::$app->controller->actionParams),
    'columns' => [
        'version_code',
        'version_name',
        [
            'attribute' => 'is_active',
            'value' => function (AndroidApps $model) {
                return Utils::decodeBoolean($model->is_active);
            },
            'filter' => Utils::booleanOptions('--All--'),
        ],
        [
            'class' => \common\widgets\grid\ActionColumn::class,
            'template' => '{download}{update}{delete}',
            'buttons'=>[
                'download' => function ($url, AndroidApps $model) {
                    return Html::a('<i class="fa fa-download text-info"></i>', $url, [
                        'data-pjax' => 0,
                        'title' => Lang::t('Download'),
                    ]);
                },
            ],
        ],
    ],
]);
?>