<?php

use backend\modules\conf\models\Notif;
use backend\modules\conf\models\NotifTypes;
use common\helpers\DateUtils;
use common\helpers\Lang;
use yii\helpers\Html;
use yii\helpers\Url;

?>
    <style type="text/css">
        .kt-notification__item.unread .kt-notification__item-title {
            color: #222!important;
            font-weight: 600;
        }
    </style>
<?php
if (!empty($data)): ?>
    <?php foreach ($data as $row):
        $notif = Notif::processTemplate($row['notif_type_id'], $row['item_id']);
        if (!$notif) {
            continue;
        }
        ?>
        <a id="notif_<?= $row['id'] ?>" href="<?= $notif['url'] ?>"
           class="kt-notification__item<?= !$row['is_read'] ? ' unread' : '' ?>"
           data-mark-as-read-url="<?= Url::to(['/conf/notif/mark-as-read', 'id' => $row['id']]) ?>" style="color: #222">
            <div class="kt-notification__item-icon">
                <i class="fas <?= NotifTypes::getIcon($row['notif_type_id']) ?> kt-font-success"></i>
            </div>
            <div class="kt-notification__item-details">
                <div class="kt-notification__item-title">
                    <?= Html::decode($notif['message']) ?>
                </div>
                <div class="kt-notification__item-time">
                    <?= DateUtils::formatToLocalDate($row['created_at']) ?>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-transparent text-center">
        <h4><?= Lang::t('You have no notifications at the moment') ?></h4>
        <i class="fa fa-bell fa-2x fa-border"></i>
    </div>
<?php endif; ?>