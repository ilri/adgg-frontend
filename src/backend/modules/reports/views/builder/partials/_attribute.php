<?php

use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $class common\models\ActiveRecord */
/* @var $attribute string */
/* @var $attributeName string */
/* @var $attributeLabel string */
/* @var $attributeTitle string */
/* @var $modelName string */
/* @var $parentModelName string */
/* @var $parentModelTitle string */

$attrTitle = $class->getAttributeLabel($attribute);
?>

<li>
    <label class="attribute kt-checkbox"
           data-original-title="<?= $attributeTitle ?>"
           data-model="<?= $modelName ?>"
           data-parent-model="<?= $parentModelName ?>"
           data-parent-model-title="<?= $parentModelTitle ?>"
           data-name="<?= $attributeName ?>"
           title="<?= $attributeLabel ?>"
           data-toggle="popover"
           data-trigger="hover"
           data-content="<?= Html::encode($class->getFieldTooltipContent($attribute)) ?>"
           data-type="<?= Html::encode($class->getFieldType($attribute)) ?>"
           data-selectoptions="<?= Html::encode(Json::encode($class->getFieldDropdownOptions($attribute))) ?>"
           data-html="true"
           data-placement="left">
        <input type="checkbox" data-name="<?= $attributeName ?>" value="<?= $attributeName ?>">
        <?= $attributeName ?>
        <span></span>
    </label>
</li>
