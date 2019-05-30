<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-21
 * Time: 9:47 PM
 */

namespace common\forms;

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

class ActiveField extends \yii\bootstrap4\ActiveField
{
    /**
     * {@inheritdoc}
     */
    public function checkbox($options = [], $enclosedByLabel = false)
    {
        Html::removeCssClass($options, 'form-control');
        Html::addCssClass($options, 'form-check-input');
        Html::addCssClass($this->labelOptions, 'form-check-label');

        if (!isset($options['template'])) {
            $this->template = ($enclosedByLabel) ? $this->checkEnclosedTemplate : $this->checkTemplate;
        } else {
            $this->template = $options['template'];
        }
        if ($this->form->layout === ActiveForm::LAYOUT_HORIZONTAL) {
            if (!isset($options['template'])) {
                $this->template = $this->checkHorizontalTemplate;
            }
            if (!empty($this->horizontalCssClasses['label'])) {
                $classes = preg_split('/\s+/', $this->horizontalCssClasses['label'], -1, PREG_SPLIT_NO_EMPTY);
                Html::removeCssClass($this->labelOptions, $classes);
            }
            Html::addCssClass($this->wrapperOptions, $this->horizontalCssClasses['offset']);
        }
        unset($options['template']);

        if ($enclosedByLabel) {
            if (isset($options['label'])) {
                $this->parts['{labelTitle}'] = $options['label'];
            }
        }

        return parent::checkbox($options, false);
    }
}