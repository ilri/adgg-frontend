<?php

use common\helpers\Lang;
use common\widgets\detailView\DetailView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\modules\reports\models\AdhocReport */
/* @var $controller \backend\controllers\BackendController */

$controller = Yii::$app->controller;
$this->title = Lang::t('Report Errors');

$form = ActiveForm::begin([
    'id' => 'my-modal-form',
    'layout' => 'horizontal',
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'fieldClass' => \common\forms\ActiveField::class,
    'options' => ['class' => 'kt-form kt-form--label-right'],
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-md-3 col-form-label',
            'offset' => 'offset-md-3',
            'wrapper' => 'col-md-6',
            'error' => '',
            'hint' => '',
        ],
    ],
]);
?>
    <div class="modal-header">
        <h5 class="modal-title"><?= Html::encode($this->title); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="hidden" id="my-modal-notif"></div>
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'table detail-view table-striped'],
            'attributes' => [
                [
                    'attribute' => 'name',
                ],
                [
                    'attribute' => 'status_remarks',
                ],
                [
                    'attribute' => 'error_message',
                ],
                [
                    'attribute' => 'error_trace',
                ],
            ],
        ]) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
<?php ActiveForm::end(); ?>