<?php

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadata;
use backend\modules\core\models\FarmMetadataType;
use common\helpers\Lang;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $farmModel Farm */
/* @var $farmmetadataModel FarmMetadata */
$type = Yii::$app->request->get('type', null);
?>

<div class="kt-portlet kt-profile">
    <div class="kt-profile__content">
        <div class="row">
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__main">
                    <div class="kt-profile__main-pic">
                        <i class="far fa-tractor fa-4x"></i>
                    </div>
                    <div class="kt-profile__main-info">
                        <div class="kt-profile__main-info-name"><?= Html::encode($farmModel->name) ?></div>
                        <div class="kt-profile__main-info-position"><?= Html::encode($farmModel->farm_type) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__contact">
                    <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-twitter">
                            <i class="fas fa-user"></i> Farmer:</span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($farmModel->farmer_name) ?></span>
                    </a>
                    <?php if (!empty($farmModel->phone)): ?>
                        <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-whatsup">
                            <i class="fas fa-phone"></i> Phone:</span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($farmModel->phone) ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($farmModel->email)): ?>
                        <a href="mailto:<?= $farmModel->email ?>" target="_blank" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon">
                            <i class="fas fa-envelope"></i> Email:</span>
                            <span class="kt-profile__contact-item-text"><?= Html::encode($farmModel->email) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-xl-4">
                <div class="kt-profile__contact">
                    <a href="#" class="kt-profile__contact-item">
                        <span class="kt-profile__contact-item-icon kt-profile__contact-item-icon-twitter">
                            <i class="fas fa-user"></i> Field Agent:</span>
                        <span class="kt-profile__contact-item-text"><?= Html::encode($farmModel->getRelationAttributeValue('fieldAgent', 'name')) ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kt-profile__nav">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
            <li class="nav-item">
                <a class="nav-link<?= empty($type) ? ' active' : '' ?>"
                   href="<?= Url::to(['view', 'id' => $farmModel->id]) ?>">
                    <?= Lang::t('Farms Details') ?>
                </a>
            </li>
            <?php foreach (FarmMetadataType::getListData('code', 'name', false, ['parent_id' => null]) as $value => $label): ?>
                <li class="nav-item">
                    <a class="nav-link<?= ($type == $value) ? ' active' : '' ?>"
                       href="<?= Url::to(['view-metadata', 'farm_id' => $farmModel->id, 'type' => $value]) ?>">
                        <?= Lang::t(' {metadataType}', ['metadataType' => $label]) ?>
                        <span class="badge badge-secondary badge-pill">
                            <?= FarmMetadata::getCount(['view-metadata', 'farm_id' => $farmModel->id, 'type' => $value]) ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="nav-item">
                <?php foreach ($farmModel->animals as $animal): ?>
                <?php endforeach; ?>
                <a class="nav-link"
                   href="<?= Url::to(['animal/index', 'farm_id' => $farmModel->id, 'country_id' => $farmModel->country_id]) ?>"
                   role="tab" target="_blank"
                   title="Click To view">
                    <?= Lang::t('Animals') ?>
                    <span class="badge badge-secondary badge-pill">
                        <?= Animal::getCount(['farm_id' => $farmModel->id]) ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false"><?= Lang::t('Actions') ?></a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <a class="dropdown-item" href="<?= Url::to(['farm/update', 'id' => $farmModel->id]) ?>">
                        <i class="fa fa-pencil text-success"></i><?= Lang::t('Update Details') ?>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>