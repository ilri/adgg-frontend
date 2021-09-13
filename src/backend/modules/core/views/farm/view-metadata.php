<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\FarmMetadata;
use backend\modules\core\models\FarmMetadataType;
use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\TableAttributesGroup;
use common\helpers\Lang;
use common\widgets\detailView\DetailView;
use common\widgets\grid\GridView;
use yii\bootstrap\Html;
use yii\helpers\Inflector;

/* @var $this \yii\web\View */
/* @var $metadataModel  FarmMetadata */
/* @var $farmModel Animal */
/* @var $controller \backend\controllers\BackendController */
/* @var $metadataTypeModel FarmMetadataType */

$controller = Yii::$app->controller;
if ($metadataModel !== null) {
    $this->title = Html::encode($metadataTypeModel->name);
} else {
    $this->title = Html::encode($farmModel->name);
}
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'country_id' => $farmModel->country_id]];
if ($metadataModel->country_id) {
    $this->params['breadcrumbs'][] = Country::getScalar('name', ['id' => $metadataModel->country_id]);
}
$this->params['breadcrumbs'][] = $this->title;
$code = Yii::$app->request->get('type');

?>
<?= $this->render('_profileHeader', ['farmModel' => $farmModel, 'type' => $metadataTypeModel->code]) ?>
<?php if ($metadataModel !== null): ?>
    <?php
    $attributeModels = TableAttribute::find()->andWhere(['farm_metadata_type' => $metadataTypeModel->code, 'is_active' => 1])->all();
    $attributeGroupIds = [];
    $attributeGroups = null;
    $showGridView = $metadataTypeModel->farmer_has_multiple ? true : false;
    foreach ($attributeModels as $attrModel) {
        $attributeGroupIds[] = $attrModel->group_id;
    }
    $attributeGroups = array_unique($attributeGroupIds);
    $values = [];
    foreach ($attributeGroups as $key => $id) {
        $attributes = $metadataModel->getViewAttributes($metadataTypeModel->code, $id, $showGridView);
        if (empty($attributes)) {
            //we do not want to show groups with no attributes.
            continue;
        }
        $groupName = TableAttributesGroup::getScalar('name', ['id' => $id]);
        //$attr=$metadataModel->getDetailViewAttributes($type,2);
        //dd($attr);
        ?>
        <div class="accordion accordion-outline" id="accordion<?= $id ?>">
            <div class="card">
                <div class="card-header" id="heading<?= $id ?>">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapse<?= $id ?>"
                         aria-expanded="false"
                         aria-controls="collapse<?= $id ?>">
                        <?= Lang::t('{group} ', ['group' => $groupName]) ?>
                    </div>
                </div>
                <div id="collapse<?= $id ?>" class="card-body-wrapper collapse" aria-labelledby="heading<?= $id ?>"
                     data-parent="#accordion<?= $id ?>" style="">
                    <div class="card-body">
                        <br/>
                        <?php if ($showGridView): ?>
                            <?= GridView::widget([
                                'searchModel' => $metadataModel,
                                'title' => $this->title,
                                'id' => $id,
                                'createButton' => ['visible' => false, 'modal' => false],
                                'toolbarButtons' => [
                                ],
                                'columns' => $attributes,
                            ]); ?>
                        <?php else: ?>
                            <?= DetailView::widget([
                                'model' => $metadataModel,
                                'options' => ['class' => 'table detail-view table-striped'],
                                'attributes' => $attributes,
                            ])
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php
    }
    ?>
<?php endif; ?>
<?php if (empty($metadataModel)): ?>
    <div class="alert alert-warning align-content-center" role="alert">No data was recorded on this event.</div>
<?php endif; ?>


<?php
$childrenOfParentType = FarmMetadataType::find()->andWhere(['parent_id' => $code, 'is_active' => 1])->all();
foreach ($childrenOfParentType as $childType) {
    $childTypeId = $childType->id;
    $childTypeCode = $childType->code;
    $childTypeName = $childType->name;
    $childTypeAttributeModels = TableAttribute::find()->andWhere(['farm_metadata_type' => $childTypeCode, 'is_active' => 1])->all();
    $childTypeAttributeGroupIds = [];
    $childTypeAttributeGroups = null;
    $childTypeClassName = FarmMetadata::getMetadataModelClassNameByType($childTypeCode);
    $childTypeMetadataModel = $childTypeClassName::findOne(['type' => $childTypeCode, 'farm_id' => $farmModel->id]);
    if ($childTypeMetadataModel !== null) {
        foreach ($childTypeAttributeModels as $childTypeAttributeModel) {
            $childTypeAttributeGroupIds[] = $childTypeAttributeModel->group_id;
        }
        $childTypeAttributeGroups = array_unique($childTypeAttributeGroupIds);
        foreach ($childTypeAttributeGroups as $childTypeKey => $childTypeGroupId) {
            $gridColumns = $childTypeMetadataModel->getViewAttributes($childTypeCode, $childTypeGroupId, true);
            if (empty($gridColumns)) {
                //we do not want to show groups with no attributes.
                continue;
            }
            $childTypeGroupName = TableAttributesGroup::getScalar('name', ['id' => $childTypeGroupId]);
            ?>
            <br>
            <div class="accordion accordion-outline" id="accordion<?= $childTypeGroupId ?>">
                <div class="card">
                    <div class="card-header" id="heading<?= $childTypeGroupId ?>">
                        <div class="card-title collapsed" data-toggle="collapse"
                             data-target="#collapse<?= $childTypeGroupId ?>"
                             aria-expanded="false" aria-controls="collapse<?= $childTypeGroupId ?>">
                            <?= $childTypeGroupName ?>
                        </div>
                    </div>
                    <div id="collapse<?= $childTypeGroupId ?>" class="card-body-wrapper collapse"
                         aria-labelledby="<?= $childTypeGroupId ?>"
                         data-parent="#accordion<?= $childTypeGroupId ?>" style="">
                        <div class="card-body">
                            <?= GridView::widget([
                                'searchModel' => $childTypeMetadataModel,
                                'title' => $childTypeGroupName,
                                'id' => $childTypeGroupId,
                                'createButton' => ['visible' => false, 'modal' => false],
                                'toolbarButtons' => [
                                ],
                                'columns' => $gridColumns,
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}
?>