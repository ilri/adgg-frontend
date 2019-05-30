<?php

use common\helpers\Lang;
use yii\bootstrap\Html;

$this->title = Lang::t('Runtime Logs');
$this->params['breadcrumbs'] = [
    $this->title
];
/* @var $this \yii\web\View */
/* @var $scope string */
/* @var $log_file string */
/* @var $log_files array */
?>
<div class="row">
    <div class="col-lg-2">
        <?= $this->render('@app/modules/conf/views/layouts/_submenu'); ?>
    </div>
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <?= Html::beginForm(['runtime'], 'post', ['id' => 'log_file_form', 'class' => 'form-inline', 'onchange' => 'MyApp.utils.triggerSubmit("#log_file_form")']) ?>
                <?= Html::dropDownList('scope', $scope, ['backend' => 'Backend', 'console' => 'Console', 'api' => 'API'], ['class' => 'form-control']) ?>
                <?= Html::dropDownList('log_file', $log_file, $log_files, ['class' => 'form-control']) ?>
                <button class="btn btn-danger btn-sm" name="clear">Clear this log</button>
                <?= Html::endForm() ?>
                <hr/>
                <pre style="max-height: 60vh;overflow-y: auto">
                    <code>
                        <?= file_exists($log_file) ? Html::encode(trim(file_get_contents($log_file))) : '' ?>
                    </code>
                </pre>
            </div>
        </div>
    </div>
</div>