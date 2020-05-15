<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\FarmMetadata;
use yii\bootstrap\Html;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $metadataModel */
/* @var $farmModel Animal */
/* @var $controller \backend\controllers\BackendController */

$type = Yii::$app->request->get('type');
$controller = Yii::$app->controller;
if ($metadataModel !== null) {
    $this->title = Html::encode(FarmMetadata::decodeType($metadataModel->type));
} else {
    $this->title = Html::encode($farmModel->name);
}
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'country_id' => $farmModel->country_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_profileHeader', ['farmModel' => $farmModel,'type'=>$type]) ?>
<?php if($type == FarmMetadata::TYPE_FEEDING_SYSTEMS_METADATA): ?>
<?= $this->render('feeding-metadata',['metadataModel'=>$metadataModel,'type'=>$type]) ?>
<?php elseif ($type == FarmMetadata::TYPE_HEALTH_METADATA):?>
    <?= $this->render('health-metadata',['metadataModel'=>$metadataModel,'type'=>$type]) ?>
<?php else :?>
    <?= $this->render('social-economic',['metadataModel'=>$metadataModel,'type'=>$type]) ?>
<?php endif; ?>
