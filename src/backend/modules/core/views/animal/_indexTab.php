<?php

use backend\modules\core\models\Animal;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller \backend\controllers\BackendController */
/* @var $model Animal */
$controller = Yii::$app->controller;
$tab = Yii::$app->request->get('type', Animal::TYPE_COW);
?>
<ul class="nav nav-tabs" role="tablist">
    <?php foreach (Animal::typeOptions(false) as $id => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= $tab == $id ? ' active' : '' ?>"
               href="<?= Url::to(['index', 'animal_type' => $id, 'type' => $id]) ?>">
                <?= strtoupper(Html::encode(Inflector::pluralize($label))) ?>
                <span class="badge badge-light badge-pill">
                <?= number_format(Animal::getCount(['type' => $id])) ?>
            </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>