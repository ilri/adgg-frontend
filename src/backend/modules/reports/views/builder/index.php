<?php

use backend\modules\core\models\Country;
use backend\modules\reports\models\ReportBuilder;
use common\helpers\Lang;
use common\helpers\Url;
use common\models\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $country_id int */
/* @var $models ReportBuilder */

$this->title = 'Report Builder';
$this->params['breadcrumbs'][] = $this->title;

?>
    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h3 class="text-muted"><?= Lang::t('REPORT BUILDER') ?>
                    : <?= strtoupper(Country::getScalar('name', ['id' => $country_id])) ?></h3>
                <hr>
                <form method="POST" id="report-builder-form">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                           value="<?= Yii::$app->request->csrfToken ?>"/>
                    <input type="hidden" name="model" id="model"/>
                    <input type="hidden" name="country_id" id="country_id" value="<?= $country_id ?>"/>
                    <div class="row" id="report-builder-container">
                        <div class="panel panel-default bs-item z-depth-2 col-md-3">
                            <div class="panel-body">
                                <?php
                                foreach ($models

                                as $name => $modelData) {
                                /* @var $class ActiveRecord */
                                $class = new $modelData['class']();
                                $title = $modelData['title'] ?? $name;
                                //$name = $class::shortClassName();
                                $attributes = $class->reportBuilderFields();
                                ?>
                                <div class="d-flex p-2 border rounded mb-3 builder-fields"
                                     data-toggle="collapse"
                                     data-target="#collapse<?= $name ?>" aria-expanded="false"
                                     aria-controls="collapse<?= $name ?>">
                                    <div>
                                        <span><?= $title ?></span>
                                    </div>
                                    <div class="ml-auto builder-chevron">
                                        <span><i class="fas fa-chevron-right"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse" id="collapse<?= $name ?>" style="">
                                <div class="card card-body kt-scroll ps ps--active-y builder-card rounded-0"
                                     style="height: 550px; overflow: hidden;" data-scroll="true">
                                    <input class="form-control-sm mb-3 search-attributes" data-model="<?= $name ?>"
                                           type="text" placeholder="Search attributes" aria-label="Search">
                                    <ul class="builder-attributes pl-0">
                                        <?php foreach ($attributes as $attr): ?>
                                            <?= $this->render('partials/_attribute', [
                                                'attribute' => $attr,
                                                'attributeTitle' => $class->getAttributeLabel($attr),
                                                'attributeName' => $attr,
                                                'attributeLabel' => $class->getAttributeLabel($attr),
                                                'class' => $class,
                                                'modelName' => $name,
                                                'parentModelName' => $name,
                                                'parentModelTitle' => $title,
                                            ])
                                            ?>
                                        <?php endforeach; ?>
                                        <?php
                                        if (count($modelData['relations'])) {
                                            $relations = $modelData['relations'];
                                            $sub_relations = $modelData['sub_relations'] ?? [];
                                            foreach ($relations as $relationName) {
                                                $relation = $class->getRelation($relationName);
                                                /* @var $relationModelClass ActiveRecord */
                                                $relationModelClass = new $relation->modelClass();
                                                //$class = $relationModelClass;
                                                $relationAttributes = $relationModelClass->reportBuilderFields();
                                                $className = $relationModelClass::shortClassName();

                                                ?>
                                                <li data-toggle="collapse"
                                                    data-target="#collapse<?= $relationName ?>"
                                                    aria-expanded="false"
                                                    aria-controls="collapse<?= $relationName ?>">
                                                    > <?= $relationName ?></li>
                                                <div class="collapse" id="collapse<?= $relationName ?>"
                                                     style="">
                                                    <ul class="builder-attributes">
                                                        <?php foreach ($relationAttributes as $attr): ?>
                                                            <?= $this->render('partials/_attribute', [
                                                                'attribute' => $attr,
                                                                'attributeTitle' => $relationName . '.' . $relationModelClass->getAttributeLabel($attr),
                                                                'attributeName' => $relationName . '.' . $attr,
                                                                'attributeLabel' => $relationModelClass->getAttributeLabel($attr),
                                                                'class' => $relationModelClass,
                                                                'modelName' => $className,
                                                                'parentModelName' => $name,
                                                                'parentModelTitle' => $title,
                                                            ])
                                                            ?>

                                                        <?php endforeach; ?>
                                                        <?php
                                                        if (count($sub_relations)) {
                                                            foreach ($sub_relations as $sub_relation => $on_options) {
                                                                $main = explode('.', $sub_relation)[0];
                                                                $sub = explode('.', $sub_relation)[1];
                                                                $sub_id = $main . '_' . $sub;
                                                                if ($main == $relationName) {
                                                                    $relation = $relationModelClass->getRelation($sub);
                                                                    $relationClass = new $relation->modelClass();
                                                                    $className = $relationModelClass::shortClassName();
                                                                    $relationAttributes = $relationClass->reportBuilderFields();
                                                                    ?>
                                                                    <li data-toggle="collapse"
                                                                        data-target="#collapse<?= $sub_id ?>"
                                                                        aria-expanded="false"
                                                                        aria-controls="collapse<?= $sub_id ?>">
                                                                        > <?= $sub ?></li>
                                                                    <div class="collapse"
                                                                         id="collapse<?= $sub_id ?>" style="">
                                                                        <ul class="builder-attributes">
                                                                            <?php foreach ($relationAttributes as $attr): ?>
                                                                                <?= $this->render('partials/_attribute', [
                                                                                    'attribute' => $attr,
                                                                                    'attributeTitle' => $main . '.' . $sub . '.' . $relationClass->getAttributeLabel($attr),
                                                                                    'attributeName' => $main . '.' . $sub . '.' . $attr,
                                                                                    'attributeLabel' => $relationClass->getAttributeLabel($attr),
                                                                                    'class' => $relationClass,
                                                                                    'modelName' => $className,
                                                                                    'parentModelName' => $name,
                                                                                    'parentModelTitle' => $title,
                                                                                ])
                                                                                ?>
                                                                            <?php endforeach; ?>
                                                                        </ul>
                                                                    </div>
                                                                    <?php

                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="panel panel-default bs-item z-depth-2 col-md-4">
                        <div class="panel-body pt-3">
                            <h3 id="selectedModel"></h3>
                            <ul id="selectedFields" class="list-group mt-3"></ul>
                        </div>
                    </div>
                    <div class="panel panel-default bs-item z-depth-2 col-md-5">
                        <div class="panel-body pt-3 pr-3 pl-3">
                            <?= $this->render('partials/_query_options') ?>

                        </div>
                    </div>

            </div>
            </form>
        </div>
    </div>
    </div>
<?php
$options = [
    'inputSelectOptions' => ReportBuilder::fieldConditionOptions(),
    'generateQueryURL' => Url::to(['/reports/builder/generate-query']),
    'saveReportURL' => Url::to(['/reports/builder/save-report']),
];
$this->registerJs("MyApp.modules.reports.reportbuilder(" . Json::encode($options) . ");");
?>