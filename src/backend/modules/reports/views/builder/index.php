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
                                foreach ($models as $name => $modelData) {
                                    /* @var $class ActiveRecord */
                                    $class = new $modelData['class']();
                                    $title = $modelData['title'] ?? $name;
                                    //$name = $class::shortClassName();
                                    $all_attributes = $class->reportBuilderFields();
                                    $main_attributes = [];
                                    $additional_attributes = [];
                                    // check if attribute is main or additional
                                    ## not necessary anymore, logic pushed to ReportBuilder
                                    foreach ($all_attributes as $attr) {
                                        if (!$class->isAdditionalAttribute($attr)) {
                                            $main_attributes[] = $attr;
                                        } else {
                                            $additional_attributes[] = $attr;
                                        }
                                    }
                                    # filter out additional_attributes field
                                    $attributes = array_filter($all_attributes, function ($attr) {
                                        return $attr != 'additional_attributes';
                                    });
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
                                    <div class="collapse" id="collapse<?= $name ?>" style="">
                                        <div class="card card-body kt-scroll ps ps--active-y builder-card rounded-0"
                                             style="height: 550px; overflow: hidden;" data-scroll="true">
                                            <ul class="builder-attributes pl-0">
                                                <?php foreach ($attributes as $attr): ?>
                                                    <?php $attrTitle = $class->getAttributeLabel($attr) ?>
                                                    <li class="attribute"
                                                        data-original-title="<?= $class->getAttributeLabel($attr) ?>"
                                                        data-model="<?= $name ?>"
                                                        data-parent-model="<?= $name ?>"
                                                        data-parent-model-title="<?= $title ?>"
                                                        data-name="<?= $attr ?>"
                                                        title="<?= $attrTitle ?>"
                                                        data-toggle="popover"
                                                        data-trigger="hover"
                                                        data-content="<?= Html::encode($class->getFieldTooltipContent($attr)) ?>"
                                                        data-html="true"
                                                        data-placement="left">
                                                        <input type="checkbox" value="<?= $attr ?>" >
                                                        <label for="<?= $attr ?>"><?= $attr ?></label>
                                                    </li>
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

                                                        # filter out additional_attributes field
                                                        $relationAttributes = array_filter($relationAttributes, function ($attr) {
                                                            return $attr != 'additional_attributes';
                                                        });

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
                                                                    <?php $attrTitle = $class->getAttributeLabel($attr) ?>
                                                                    <li class="attribute"
                                                                        data-original-title="<?= $relationName . '.' . $relationModelClass->getAttributeLabel($attr) ?>"
                                                                        data-model="<?= $className ?>"
                                                                        data-parent-model="<?= $name ?>"
                                                                        data-parent-model-title="<?= $title ?>"
                                                                        data-name="<?= $relationName . '.' . $attr ?>"
                                                                        title="<?= $attrTitle ?>"
                                                                        data-toggle="popover"
                                                                        data-trigger="hover"
                                                                        data-content="<?= Html::encode($relationModelClass->getFieldTooltipContent($attr)) ?>"
                                                                        data-html="true"
                                                                        data-placement="left">
                                                                        <input type="checkbox" value="<?= $relationName . '.' . $attr ?>"/>
                                                                        <label for="<?= $relationName . '.' . $attr ?>"><?= $attr ?></label>
                                                                    </li>
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
                                                                                        <?php $attrTitle = $class->getAttributeLabel($attr) ?>
                                                                                        <li class="attribute"
                                                                                            data-original-title="<?= $main . '.' . $sub . '.' . $relationClass->getAttributeLabel($attr) ?>"
                                                                                            data-model="<?= $className ?>"
                                                                                            data-parent-model="<?= $name ?>"
                                                                                            data-parent-model-title="<?= $title ?>"
                                                                                            data-name="<?= $main . '.' . $sub . '.' . $attr ?>"
                                                                                            title="<?= $attrTitle ?>"
                                                                                            data-toggle="popover"
                                                                                            data-trigger="hover"
                                                                                            data-content="<?= Html::encode($relationClass->getFieldTooltipContent($attr)) ?>"
                                                                                            data-html="true"
                                                                                            data-placement="left">
                                                                                            <input type="checkbox"
                                                                                                   value="<?= $main . '.' . $sub . '.' . $attr ?>"><label
                                                                                                    for="<?= $main . '.' . $sub . '.' . $attr ?>"><?= $attr ?></label>
                                                                                        </li>
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
                                <div id="queryOptions" class="hidden">
                                    <h3>Query Options</h3>
                                    <div class="row row-no-gutters mt-3">
                                        <div class="col-md-8"><label for="limit">Limit: </label></div>
                                    </div>
                                    <div class="row row-no-gutters mb-2">
                                        <div class="col-md-10"><input name="limit" id="limit" type="number"
                                                                      value=""
                                                                      class="form-control form-control-sm"/>
                                        </div>
                                    </div>
                                    <div class="row row-no-gutters mt-2">
                                        <div class="col-md-3"><label for="orderby">Order By: </label></div>

                                    </div>
                                    <div class="row row-no-gutters mt-2">
                                        <div class="col-md-10">
                                            <select name="orderby" id="orderby" type="text"
                                                    class="form-control form-control-sm"></select>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button id="askForName" role="button" class="btn btn-primary col-md-10"
                                                data-toggle="modal" data-target="#inputName">Generate & Save
                                            Report
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <button id="generateQuery" role="button"
                                                class="btn btn-generatequery col-md-10">
                                            Preview Query
                                        </button>
                                    </div>

                                    <div class="row card card-body mt-4 mb-4 col-md-10 hidden"
                                         id="previewQueryCard">
                                        <div class="bd-clipboard">
                                            <button type="button" data-clipboard-target="#queryHolder"
                                                    class="btn-clipboard">Copy
                                            </button>
                                        </div>
                                        <figure class="highlight hidden">
                                    <pre class="pre-scrollable">
                                        <code id="" class="language-sql text-wrap word-wrap" data-lang="sql"></code>
                                    </pre>
                                        </figure>
                                        <textarea id="queryHolder" class="language-sql text-wrap word-wrap"
                                                  style="width: 100%"></textarea>
                                    </div>

                                    <div id="inputName" role="dialog" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <!--Modal header-->
                                                <div class="modal-header mt-2">
                                                    <h4 class="modal-title">Save Generated Report: </h4>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only">Close</span>
                                                    </button>

                                                </div>
                                                <!--Modal Body-->
                                                <div class="modal-body">
                                                    <div id="reportNameMessage"></div>
                                                    <div class="form-group required">
                                                        <div class="row row-no-gutters mt-2">
                                                            <div class="col-md-10 mx-auto"><label for="name"
                                                                                                  class="control-group">Report
                                                                    Name</label>
                                                            </div>
                                                        </div>
                                                        <div class="row row-no-gutters mt-2">
                                                            <div class="col-md-10 mx-auto"><input name="name" id="name"
                                                                                                  type="text" value=""
                                                                                                  class="form-control form-control-sm"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button id="saveReport" role="button"
                                                            class="btn btn-success col-md-10 mx-auto">Generate & Save
                                                        Report
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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