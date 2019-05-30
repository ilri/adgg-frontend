<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-24
 * Time: 9:16 AM
 */

use backend\modules\core\models\RegistrationDocument;
use common\helpers\DateUtils;
use common\helpers\Utils;
use yii\bootstrap4\Html;

/* @var $model RegistrationDocument */
?>
<div class="row">
    <div class="col-lg-4">
        <table class="table detail-view">
            <tbody>
            <tr>
                <th><?= $model->getAttributeLabel('document_no') ?></th>
                <td><?= Html::encode($model->document_no) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('doc_type_id') ?></th>
                <td><?= Html::encode($model->getRelationAttributeValue('docType', 'name')) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('description') ?></th>
                <td><?= Html::encode($model->description) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('start_date') ?></th>
                <td><?= DateUtils::formatDate($model->start_date, 'd-M-Y') ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('renewal_date') ?></th>
                <td><?= DateUtils::formatDate($model->start_date, 'd-M-Y') ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <table class="table detail-view">
            <tbody>
            <tr>
                <th><?= $model->getAttributeLabel('is_active') ?></th>
                <td><?= Html::tag('i', '', ['class' => $model->is_active ? 'fas fa-check' : 'fas fa-times']) ?> <?= Utils::decodeBoolean($model->is_active) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('is_approved') ?></th>
                <td><?= Html::tag('i', '', ['class' => $model->is_approved ? 'fas fa-check' : 'fas fa-times']) ?> <?= Utils::decodeBoolean($model->is_approved) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('approval_notes') ?></th>
                <td><?= Html::encode($model->approval_notes) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('approved_by') ?></th>
                <td><?= Html::encode($model->getRelationAttributeValue('approvedBy', 'name')) ?></td>
            </tr>
            <tr>
                <th><?= $model->getAttributeLabel('created_at') ?></th>
                <td><?= DateUtils::formatToLocalDate($model->created_at) ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <div class="kt-iconbox kt-iconbox--elevate">
            <div class="kt-iconbox__icon">
                <div class="kt-iconbox__icon-bg"></div>
                <i class="flaticon-file-2" style="z-index: 0;"></i>
            </div>
            <div class="kt-iconbox__title">
                <a class="btn btn-outline-secondary"
                   href="<?= \yii\helpers\Url::to(['registration-document/download', 'id' => $model->uuid]) ?>" data-pjax="0">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>
