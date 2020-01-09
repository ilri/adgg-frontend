<?php

/* @var $this yii\web\View */

use backend\modules\reports\models\ReportBuilder;
use backend\modules\reports\models\Reports;
use common\helpers\Lang;
use common\helpers\Url;
use common\models\ActiveRecord;
use yii\bootstrap\Html;
use yii\helpers\Json;

$this->title = 'Report Builder';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-12">
        <div class="well">
            <h3 class="text-muted"><?= Lang::t('REPORT BUILDER') ?></h3>
            <hr>
            <form method="POST" id="report-builder-form" >
                <input type="hidden" name="model" id="model" />
            <div class="row">
                <div class="panel panel-default bs-item z-depth-2 col-md-4">
                    <div class="panel-body">
                        <?php
                        foreach ($models as $name => $modelData){
                            /* @var $class ActiveRecord */
                            $class = new $modelData['class']();
                            //$name = $class::shortClassName();
                            $attributes = $class->attributes();
                        ?>
                            <p>
                                <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#collapse<?= $name ?>" aria-expanded="false" aria-controls="collapse<?= $name ?>">
                                        <?= $name ?>
                                </button>
                            </p>
                            <div class="collapse" id="collapse<?= $name ?>" style="">
                                <div class="card card-body">
                                    <ul>
                                    <?php foreach ($attributes as $attr): ?>
                                        <li class="attribute" data-model="<?= $name ?>" data-parent-model="<?= $name ?>" data-name="<?= $attr ?>"><?= $attr ?></li>
                                    <?php endforeach; ?>
                                    <?php
                                    if(count($modelData['relations'])){
                                        $relations = $modelData['relations'];
                                        foreach ($relations as $relationName){
                                            $relation = $class->getRelation($relationName);
                                            /* @var $relationModelClass ActiveRecord */
                                            $relationModelClass = new $relation->modelClass();
                                            $relationAttributes = $relationModelClass->attributes();
                                            $className = $relationModelClass::shortClassName();
                                            ?>
                                            <li data-toggle="collapse" data-target="#collapse<?= $relationName ?>" aria-expanded="false" aria-controls="collapse<?= $relationName ?>"> > <?= $relationName ?></li>
                                            <div class="collapse" id="collapse<?= $relationName ?>" style="">
                                                <ul>
                                                    <?php foreach ($relationAttributes as $attr): ?>
                                                        <li class="attribute" data-model="<?= $className ?>" data-parent-model="<?= $name ?>" data-name="<?= $relationName.'.'.$attr ?>"><?= $attr ?></li>
                                                    <?php endforeach; ?>
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
                <div class="panel panel-default bs-item z-depth-2 col-md-5">
                    <div class="panel-body pt-3">
                        <h3 id="selectedModel"></h3>
                        <ul id="selectedFields" class="list-group"></ul>
                    </div>
                </div>
                <div class="panel panel-default bs-item z-depth-2 col-md-3">
                    <div class="panel-body pt-3">
                        <div id="queryOptions" class="hidden">
                            <h3>Query Options</h3>
                            <div class="row row-no-gutters mb-2">
                                <div class="col-md-3"><label>Limit: </label></div>
                                <div class="col-md-8"><input name="limit" type="text" value="100" class="form-control" /></div>
                            </div>
                            <div class="row row-no-gutters mt-2">
                                <div class="col-md-3"><label>Order By: </label></div>
                                <div class="col-md-8"><input name="orderby" type="text" value="" class="form-control" /></div>
                            </div>
                            <div class="mt-5">
                                <button id="generateQuery" role="button" class="btn btn-primary col-md-8 offset-3">Generate Query</button>
                            </div>
                            <div class="row card card-body mt-4" id="queryHolder"></div>

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
];
$this->registerJs("MyApp.modules.reports.reportbuilder(" . Json::encode($options) . ");");
?>