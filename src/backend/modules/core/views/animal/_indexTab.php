<?php

use backend\modules\core\Constants;
use backend\modules\core\models\Animal;
use backend\modules\core\models\ListType;
use backend\modules\core\models\LookupList;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

/* @var $controller \backend\controllers\BackendController */
/* @var $model Animal */
$controller = Yii::$app->controller;
$tab = Yii::$app->request->get('tab', Constants::TAB_ALL);
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link<?= $tab == Constants::TAB_ALL ? ' active' : '' ?>"
           href="<?= Url::to(['index', 'tab' => Constants::TAB_ALL]) ?>">
            <?= Lang::t('ALL ANIMALS') ?>
            <span class="badge badge-light badge-pill">
                <?= number_format(Animal::getCount([])) ?>
            </span>
        </a>
    </li>
    <?php foreach (LookupList::getList(ListType::LIST_TYPE_ANIMAL_TYPES, false) as $id => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= $tab == $id ? ' active' : '' ?>"
               href="<?= Url::to(['index', 'animal_type' => $id, 'tab' => $id]) ?>">
                <?= strtoupper(Html::encode(Inflector::pluralize($label))) ?>
                <span class="badge badge-light badge-pill">
                <?= number_format(Animal::getCount(['animal_type' => $id])) ?>
            </span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>