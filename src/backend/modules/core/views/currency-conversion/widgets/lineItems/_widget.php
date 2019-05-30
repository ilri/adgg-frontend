<?php

use common\helpers\Lang;
use common\widgets\lineItem\LineItem;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this \yii\web\View */
/* @var $controller \backend\controllers\BackendController */
/* @var $lineItemModels \backend\modules\core\models\CurrencyConversion[] */
/* @var $model \backend\modules\core\models\Currency */
$controller = Yii::$app->controller;

$form = ActiveForm::begin([
    'id' => 'currency-conversion-form',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'enableError' => false,
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-md-3',
            'offset' => 'col-md-offset-3',
            'wrapper' => 'col-md-8',
            'error' => '',
            'hint' => '',
        ],
    ],
]);
?>

<?= LineItem::widget([
    'activeForm' => $form,
    'title' => 'Default Currency: ' . Html::encode($model->name),
    'parentModel' => $model,
    'tableOptions' => ['class' => 'table'],
    'lineItemModels' => $lineItemModels,
    'showLineItemsOnPageLoad' => true,
    'showAddLineButton' => false,
    'primaryKeyAttribute' => 'id',
    'foreignKeyAttribute' => 'default_currency',
    'showSaveButton' => false,
    'showDeleteButton' => false,
    'finishButtonLabel' => Lang::t('UPDATE'),
    'template' => $this->render('_template'),
])
?>
<?php
ActiveForm::end();
?>