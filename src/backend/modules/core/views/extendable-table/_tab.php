<?php

use backend\modules\core\models\ExtendableTable;
use common\helpers\Lang;
use yii\helpers\Url;

$table_id = Yii::$app->request->get('table_id', ExtendableTable::TABLE_FARM);
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
</ul>