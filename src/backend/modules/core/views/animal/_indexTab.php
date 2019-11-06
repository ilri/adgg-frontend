<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\core\models\Choices;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller \backend\controllers\BackendController */
/* @var $model Animal */
$controller = Yii::$app->controller;
$animalType = Yii::$app->request->get('animal_type', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= empty($animalType) ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'animal_type' => null]) ?>">
            <?= Lang::t('All Animals') ?>
            <span class="badge badge-important badge-pill">
                (<?= number_format(Animal::getCount([])) ?>)
            </span>
        </a>
    </li>
    <?php foreach (Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, false) as $value => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= $animalType == $value ? ' active' : '' ?>"
               href="<?= Url::to(['index', 'animal_type' => $value]) ?>">
                <?= strtoupper(Html::encode(Inflector::pluralize($label))) ?>
                <span class="badge badge-important badge-pill">
                (<?= number_format(Animal::getCount(['animal_type' => $value])) ?>)
            </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>