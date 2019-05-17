<?php

use backend\modules\help\Help;
use backend\modules\help\models\HelpModules;
use common\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $activeModule HelpModules */
/* @var $allModules HelpModules[] */
/* @var $controller \backend\controllers\BackendController */

$this->title = 'Help Information';
$this->params['breadcrumbs'][] = $this->title;
$controller = Yii::$app->controller;
?>

<?php

$activeModule = empty($activeModule) ? $controller->helpModuleName : $activeModule;
$activeModuleName = $activeModule instanceof HelpModules ? $activeModule->name : $activeModule;

?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('@helpModule/views/default/layouts/submenu',
            ['allModules' => $allModules, 'activeModule' => $activeModule]); ?>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-12">
                <div id="custom-search-input">
                    <form action="<?= Url::to(['search']) ?>" method="get" id="help-search-form" class="form-horizontal"
                          role="form">
                        <div class="input-group col-md-12">
                            <input type="text" name="q" id="help-search-input" class="form-control input-md"
                                   placeholder="Enter a search term and hit enter"/>
                            <span class="input-group-btn">
                        <button class="btn btn-info btn-lg" id="help-search-button" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12" id="search-content">
            <?php if (Help::isDefault($activeModule)): ?>
                <h1>General System Help Information</h1>
                <p>
                    This section contains general help articles that will apply to many areas of the system
                </p>
                <hr>
                <?= $this->render('_help', ['contents' => $moduleContent]) ?>
            <?php else: ?>
                <?php if (isset($moduleContent)): ?>
                    <?= $this->render('_help', ['contents' => $moduleContent]) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

