<?php

/* @var $this yii\web\View */

use backend\modules\core\Constants;
use backend\modules\reports\models\Reports;
use common\helpers\Lang;
use common\helpers\Url;
use yii\bootstrap\Html;

/* @var $type string */
/* @var $country_id int */
/* @var $filterOptions array */

$this->title = 'Standard Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if (Yii::$app->user->canView(Constants::RES_REPORT_BUILDER)): ?>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('reports/' . $type, ['country_id' => $country_id, 'filterOptions' => $filterOptions]) ?>
    </div>
</div>
<?php endif; ?>