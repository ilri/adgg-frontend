<?php

use backend\modules\core\models\OdkForm;
use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\core\models\OdkForm */

$this->title = Lang::t('JSON File Details');
$this->params['breadcrumbs'][] = ['label' => 'ODK-Form-Files', 'url' => ['index', 'country_id' => $model->country_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
    <style type="text/css">
        pre {
            outline: 1px solid #ccc;
            padding: 5px;
            margin: 5px;
        }

        .string {
            color: green;
        }

        .number {
            color: darkorange;
        }

        .boolean {
            color: blue;
        }

        .null {
            color: magenta;
        }

        .key {
            color: red;
        }
    </style>


    <div class="accordion accordion-outline" id="accordion1">
        <div class="card">
            <div class="card-header" id="headingOne">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne"
                     aria-expanded="false"
                     aria-controls="collapseOne">
                    ODK Form Details
                </div>
            </div>
            <div id="collapseOne" class="card-body-wrapper collapse show" aria-labelledby="headingOne"
                 data-parent="#accordion1" style="">
                <div class="card-body">
                    <br/>
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table detail-view table-striped'],
                        'attributes' => [
                            [
                                'attribute' => 'id',
                            ],
                            [
                                'attribute' => 'form_uuid',
                            ],
                            [
                                'attribute' => 'form_version',
                            ],
                            [
                                'attribute' => 'is_processed',
                                'format' => 'boolean',
                            ],
                            [
                                'attribute' => 'processed_at',
                                'value' => DateUtils::formatToLocalDate($model->processed_at),
                            ],
                            [
                                'attribute' => 'country_id',
                                'value' => $model->getRelationAttributeValue('country', 'name'),
                            ],
                            [
                                'attribute' => 'has_errors',
                                'format' => 'boolean',
                            ],
                            [
                                'attribute' => 'error_message',
                            ],
                            [
                                'attribute' => 'created_at',
                                'value' => DateUtils::formatToLocalDate($model->created_at),
                            ],
                            [
                                'attribute' => 'processed_at',
                                'value' => DateUtils::formatToLocalDate($model->processed_at),
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="accordion accordion-outline" id="accordion2">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo"
                     aria-expanded="false"
                     aria-controls="collapseTwo">
                    Form JSON Data
                </div>
            </div>
            <div id="collapseTwo" class="card-body-wrapper collapse" aria-labelledby="headingTwo"
                 data-parent="#accordion2" style="">
                <div class="card-body">
                    <br/>
                    <div class="row">
                        <div class="col-lg-12">
                            <pre class="show-pretty-json"
                                 data-json='<?= json_encode($model->form_data, JSON_FORCE_OBJECT) ?>'></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if ($model->has_errors): ?>
    <br>
    <div class="accordion accordion-outline" id="accordion3">
        <div class="card">
            <div class="card-header" id="headingThree">
                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree"
                     aria-expanded="false"
                     aria-controls="collapseThree">
                    Processing details
                </div>
            </div>
            <div id="collapseThree" class="card-body-wrapper collapse" aria-labelledby="headingThree"
                 data-parent="#accordion3" style="">
                <div class="card-body">
                    <br/>
                    <?php if (!empty($model->farm_data)): ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Farm data</h3>
                                <pre class="show-pretty-json"
                                     data-json='<?= json_encode(OdkForm::cleanDataModelJson($model->farm_data), JSON_FORCE_OBJECT) ?>'></pre>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($model->farm_metadata)): ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Farm metadata</h3>
                                <pre class="show-pretty-json"
                                     data-json='<?= json_encode($model->farm_metadata, JSON_FORCE_OBJECT) ?>'></pre>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($model->animals_data)): ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Animal data</h3>
                                <pre class="show-pretty-json"
                                     data-json='<?= json_encode($model->animals_data, JSON_FORCE_OBJECT) ?>'></pre>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($model->animal_events_data)): ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3>Animal events data</h3>
                                <pre class="show-pretty-json"
                                     data-json='<?= json_encode($model->animal_events_data, JSON_FORCE_OBJECT) ?>'></pre>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
$options = [];
$this->registerJs("MyApp.modules.core.showPrettyOdkJson(" . Json::encode($options) . ");");
?>