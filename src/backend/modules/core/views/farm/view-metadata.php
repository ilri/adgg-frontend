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
/* @var $parentMetadataModel  FarmMetadata */
/* @var $childTypeMetadataModel  FarmMetadata */
/* @var $farmModel Animal */
/* @var $controller \backend\controllers\BackendController */
$type = Yii::$app->request->get('type');
$controller = Yii::$app->controller;
if ($parentMetadataModel !== null) {
    $this->title = Html::encode(FarmMetadata::decodeType($parentMetadataModel->type));
} else {
    $this->title = Html::encode($farmModel->name);
}
$this->params['breadcrumbs'][] = ['label' => Inflector::pluralize($controller->resourceLabel), 'url' => ['index', 'country_id' => $farmModel->country_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_profileHeader', ['farmModel' => $farmModel, 'type' => $type]) ?>
<?php if ($parentMetadataModel !== null): ?>
    <?php
    $parentModelAttributes = TableAttribute::find()->andWhere(['farm_metadata_type' => $type, 'is_active' => 1])->all();
    $parentModelGroupIds = [];
    $parentAttributesGroups = null;
    foreach ($parentModelAttributes as $attrModel) {
        $parentModelGroupIds[] = $attrModel->group_id;
    }
    $parentAttributesGroups = array_unique($parentModelGroupIds);
    foreach ($parentAttributesGroups as $key => $parentAttributesGroupId) {
        $parentAttributes = $parentMetadataModel->getViewAttributes($type, $parentAttributesGroupId, false);
        if (empty($parentAttributes)) {
            //we do not want to show groups with no attributes.
            continue;
        }
        $parentGroupName = TableAttributesGroup::getScalar('name', ['id' => $parentAttributesGroupId]);
        ?>
        <div class="accordion accordion-outline" id="accordion<?= $parentAttributesGroupId ?>">
            <div class="card">
                <div class="card-header" id="heading<?= $parentAttributesGroupId ?>">
                    <div class="card-title collapsed" data-toggle="collapse"
                         data-target="#collapse<?= $parentAttributesGroupId ?>"
                         aria-expanded="false"
                         aria-controls="collapse<?= $parentAttributesGroupId ?>">
                        <?= Lang::t('{parentGroup} ', ['parentGroup' => $parentGroupName]) ?>
                    </div>
                </div>
                <div id="collapse<?= $parentAttributesGroupId ?>" class="card-body-wrapper collapse"
                     aria-labelledby="heading<?= $parentAttributesGroupId ?>"
                     data-parent="#accordion<?= $parentAttributesGroupId ?>" style="">
                    <div class="card-body">
                        <br/>
                        <?= DetailView::widget([
                            'model' => $parentMetadataModel,
                            'options' => ['class' => 'table detail-view table-striped'],
                            'attributes' => $parentAttributes,
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
    $childrenOfParentType= FarmMetadataType::find()->andWhere(['parent_id'=>$type, 'is_active'=>1,'farmer_has_multiple'=>1])->all();
    foreach ($childrenOfParentType as $childType) {
        $childTypeId = $childType->id;
        $childTypeCode = $childType->code;
        $childTypeName = $childType->name;
        $childTypeAttributeModels = TableAttribute::find()->andWhere(['farm_metadata_type' => $childTypeId, 'is_active' => 1])->all();
        $childTypeAttributeGroupIds = [];
        $childTypeAttributeGroups = null;
        $childTypeClassName = FarmMetadata::getMetadataModelClassNameByType($childTypeId);
        $childTypeMetadataModel = $childTypeClassName::findOne(['type' => $childTypeId, 'farm_id' => $farmModel->id]);
        foreach ($childTypeAttributeModels as $childTypeAttributeModel) {
            $childTypeAttributeGroupIds[] = $childTypeAttributeModel->group_id;
        }
        $childTypeAttributeGroups = array_unique($childTypeAttributeGroupIds);
        foreach ($childTypeAttributeGroups as $childTypeKey => $childTypeGroupId)
        {
            $gridColumns = $childTypeMetadataModel->getViewAttributes($childTypeId, $childTypeGroupId, true);
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
                            'id'=>$childTypeGroupId,
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
    <?= '<h4>' . Lang::t('No {metadataType} Data for this farm', ['metadataType' => Html::encode(FarmMetadata::decodeType($type))]) . '</h4>' ?>
<?php endif; ?>

