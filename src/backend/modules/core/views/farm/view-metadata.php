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
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_profileHeader', ['farmModel' => $farmModel, 'type' => $metadataTypeModel->code]) ?>
<?php if ($metadataModel !== null): ?>
    <?php
    $attributeModels = TableAttribute::find()->andWhere(['farm_metadata_type' => $metadataTypeModel->code, 'is_active' => 1])->all();
    $attributeGroupIds = [];
    $attributeGroups = null;
    foreach ($attributeModels as $attrModel) {
        $attributeGroupIds[] = $attrModel->group_id;
    }
    $attributeGroups = array_unique($attributeGroupIds);
    $values = [];
    foreach ($attributeGroups as $key => $id) {
        $attributes = $metadataModel->getViewAttributes($metadataTypeModel->code, $id, false);
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
                        <?= DetailView::widget([
                            'model' => $metadataModel,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => $attributes,
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php
    }
    ?>
    <?php
    $childrenOfParentType = FarmMetadataType::find()->andWhere(['parent_id' => $metadataTypeModel->code, 'is_active' => 1, 'farmer_has_multiple' => 1])->all();
    foreach ($childrenOfParentType as $childType) {
        $childTypeId = $childType->id;
        $childTypeCode = $childType->code;
        $childTypeName = $childType->name;
        $childTypeAttributeModels = TableAttribute::find()->andWhere(['farm_metadata_type' => $childTypeCode, 'is_active' => 1])->all();
        $childTypeAttributeGroupIds = [];
        $childTypeAttributeGroups = null;
        $childTypeClassName = FarmMetadata::getMetadataModelClassNameByType($childTypeCode);
        $childTypeMetadataModel = $childTypeClassName::findOne(['type' => $childTypeCode, 'farm_id' => $farmModel->id]);
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
            <div class="row" id="<?= $childTypeGroupId ?>">
                <div class="col-lg-12">
                    <div class="tab-content">
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
            <br>
            <?php
        }
    }
    ?>
<?php else: ?>
    <?= '<h4>' . Lang::t('No {metadataType} Data for this farm', ['metadataType' => Html::encode($metadataTypeModel->name)]) . '</h4>' ?>
<?php endif; ?>

