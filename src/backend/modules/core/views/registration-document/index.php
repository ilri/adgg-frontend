<?php

use backend\modules\core\models\Organization;
use yii\bootstrap\Html;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $orgModel Organization */
/* @var $searchModel \backend\modules\core\models\RegistrationDocument */
/* @var $controller \backend\controllers\BackendController */
$controller = Yii::$app->controller;
$this->title = Inflector::pluralize($controller->resourceLabel);
$this->params['breadcrumbs'][] = ['label' => Html::encode($orgModel->name), 'url' => ['organization/view', 'id' => $orgModel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('@coreModule/views/organization/_profileHeader', ['model' => $orgModel]) ?>
<?= $this->render('_grid', ['model' => $searchModel]) ?>
