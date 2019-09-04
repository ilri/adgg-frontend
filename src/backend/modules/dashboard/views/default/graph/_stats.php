<?php

use backend\modules\core\models\Animal;
use common\helpers\Lang;

/* @var $filters array */
?>
<ul class="list-group">
    <li class="list-group-item active">
        <?= Lang::t('Animal stats') ?>
    </li>
    <li class="list-group-item">
        <?= Lang::t('Today') ?>
        <strong>
            <?= number_format(Animal::getDashboardStats(Animal::STATS_TODAY, false, $filters)) ?>
        </strong>
    </li>
    <li class="list-group-item">
        <?= Lang::t('This week') ?>
        <strong>
            <?= number_format(Animal::getDashboardStats(Animal::STATS_THIS_WEEK, false, $filters)) ?>
        </strong>
    </li>
    <li class="list-group-item">
        <?= Lang::t('Last week') ?>
        <strong>
            <?= number_format(Animal::getDashboardStats(Animal::STATS_LAST_WEEK, false, $filters)) ?>
        </strong>
    </li>
    <li class="list-group-item">
        <?= Lang::t('This month') ?>
        <strong>
            <?= number_format(Animal::getDashboardStats(Animal::STATS_THIS_MONTH, false, $filters)) ?>
        </strong>
    </li>
    <li class="list-group-item">
        <?= Lang::t('Last month') ?>
        <strong>
            <?= number_format(Animal::getDashboardStats(Animal::STATS_LAST_MONTH, false, $filters)) ?>
        </strong>
    </li>
    <li class="list-group-item">
        <?= Lang::t('This year') ?>
        <strong>
            <?= number_format(Animal::getDashboardStats(Animal::STATS_THIS_YEAR, false, $filters)) ?>
        </strong>
    </li>
    <li class="list-group-item">
        <?= Lang::t('Last year') ?>
        <strong>
            <?= number_format(Animal::getDashboardStats(Animal::STATS_LAST_YEAR, false, $filters)) ?>
        </strong>
    </li>
</ul>
