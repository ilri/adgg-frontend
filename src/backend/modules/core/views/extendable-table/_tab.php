<?php

use backend\modules\auth\Session;
use backend\modules\core\models\ExtendableTable;
use common\helpers\Lang;
use yii\helpers\Url;

$table_id = Yii::$app->request->get('table_id', null);
?>
<ul class="nav nav-tabs" role="tablist">
    <?php foreach (ExtendableTable::tableOptions(false) as $id => $label): ?>
        <li class="nav-item">
            <a class="nav-link<?= $table_id == $id ? ' active' : '' ?>"
               href="<?= Url::to(['extendable-table/index', 'table_id' => $id]) ?>">
                <?= Lang::t($label) ?>
            </a>
        </li>
    <?php endforeach; ?>
    <?php if(Session::isDev()): ?>
        <li class="nav-item">
            <a class="nav-link<?= Yii::$app->controller->id === 'farm-metadata-type' ? ' active' : '' ?>"
               href="<?= Url::to(['/core/farm-metadata-type/index']) ?>">
                <?= Lang::t('Farm Metadata Types') ?>
            </a>
        </li>
    <?php endif; ?>
</ul>