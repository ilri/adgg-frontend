<?php

use common\helpers\Lang;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model \backend\modules\core\models\Currency */
/* @var $lineItemModels \backend\modules\core\models\CurrencyConversion[] */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Lang::t('Update {resource}', ['resource' => $controller->resourceLabel]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@confModule/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-6">
        <?= $this->render('@coreModule/views/layouts/_masterDataSubmenu'); ?>
        <div class="tab-content">
            <div class="shadow-lg p-3 mb-5 bg-white rounded">
                <div class="card-bodys">
                    <?= Html::beginForm(['update'], 'get', ['class' => 'form-inline']) ?>
                    <div class="form-group">
                        <?= Html::label(Lang::t('Default Currency') . ':', null, ['class' => 'control-label']) ?>
                        <?= Html::dropDownList('default_currency', $model->iso3, \backend\modules\core\models\Currency::getListData(), [
                            'class' => '',
                            'onchange' => 'this.form.submit();'
                        ]) ?>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
            <?= $this->render('widgets/lineItems/_widget', ['lineItemModels' => $lineItemModels, 'model' => $model]); ?>
        </div>
    </div>
</div>