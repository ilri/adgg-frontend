<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 23/11/18
 * Time: 00:52
 */

namespace common\widgets\select2;


use yii\web\JsExpression;

class Select2 extends \kartik\select2\Select2
{
    /**
     * @var bool
     */
    public $modal = false;

    //public $bsVersion='4.x';

    /**
     * @var string
     */
    public $dropdownParentSelector;

    public function run()
    {
        $this->theme = self::THEME_BOOTSTRAP;
        if (!empty($this->dropdownParentSelector)) {
            $this->pluginOptions['dropdownParent'] = new JsExpression("$('{$this->dropdownParentSelector}')");
        } elseif ($this->modal) {
            $this->pluginOptions['dropdownParent'] = new JsExpression("$('#my-bs-modal')");
        }
        parent::run();
    }

}