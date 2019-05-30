<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/05
 * Time: 3:01 AM
 */

namespace common\widgets\grid;

use backend\controllers\BackendController;
use common\helpers\Lang;
use common\models\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActionColumn extends \kartik\grid\ActionColumn
{

    public $width = '100px';

    public $mergeHeader = false;

    /**
     * Render default action buttons
     *
     * @return string
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url) {
                $options = $this->viewOptions;
                if (isset($this->viewOptions['visible']) && $this->viewOptions['visible'] === false) {
                    return '';
                }
                if (!isset($options['class'])) {
                    //$options['class'] = 'btn btn-sm btn-clean btn-icon btn-icon-md';
                }
                $title = Lang::t('View');
                $icon = '<i class="fas fa-eye"></i>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                /* @var $model ActiveRecord */
                $options = $this->updateOptions;
                if (!isset($options['modal'])) {
                    $options['modal'] = true;
                }
                $title = Lang::t('Update');
                $icon = '<i class="fas fa-edit"></i>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(['title' => $title, 'data-pjax' => '0'], $options);
                if ($options['modal']) {
                    $options = ArrayHelper::merge(['data-href' => $url, 'data-toggle' => 'modal', 'data-grid' => $model->getPjaxWidgetId()], $options);
                    $url = '#';
                    unset($options['modal']);
                }
                /* @var $controller BackendController */
                $controller = Yii::$app->controller;
                $visible = Yii::$app->user->canUpdate($controller->resource);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    $link = $visible ? Html::a($label, $url, $options) : '';
                    $li = !empty($link) ? '<li>' . $link . '</li>' . PHP_EOL : '';
                    return $li;
                } else {
                    return $visible ? Html::a($label, $url, $options) : '';
                }
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                /* @var $model ActiveRecord */
                $options = $this->deleteOptions;
                if (isset($this->deleteOptions['visible']) && $this->deleteOptions['visible'] === false) {
                    return '';
                }
                if (!isset($options['class'])) {
                    //$options['class'] = 'btn btn-sm btn-clean btn-icon btn-icon-md';
                }
                $title = Lang::t('Delete');
                $icon = '<i class="fas fa-trash"></i>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = ArrayHelper::merge(
                    [
                        'title' => $title,
                        'data-confirm-message' => Lang::t('DELETE_CONFIRM'),
                        'data-href' => $url,
                        'data-pjax' => '0',
                        'class' => 'grid-update',
                        'data-grid' => $model->getPjaxWidgetId(),
                    ],
                    $options
                );
                $visible = (isset($this->deleteOptions['visible']) && $this->deleteOptions['visible']) || Yii::$app->user->canDelete($this->grid->view->context->resource);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    $link = $visible ? Html::a($label, 'javascript:void(0);', $options) : '';
                    $li = !empty($link) ? '<li>' . $link . '</li>' . PHP_EOL : '';
                    return $li;
                } else {
                    return $visible ? Html::a($label, 'javascript:void(0);', $options) : '';
                }
            };
        }
    }
}