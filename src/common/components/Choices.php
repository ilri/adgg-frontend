<?php

namespace common\components;


use backend\modules\core\models\Choices as ChoicesModel;
use common\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-11-06
 * Time: 10:22 PM
 */
class Choices extends \yii\base\Component
{

    public $setChoicesOnInit = true;
    /**
     * @var array|null
     */
    private $_choices;

    public function init()
    {
        parent::init();
        if ($this->setChoicesOnInit) {
            $this->setChoices();
        }
    }

    protected function setChoices()
    {
        $choices = ChoicesModel::getData(['value', 'label', 'list_type_id'], ['is_active' => 1]);
        $this->_choices = $choices;
    }

    /**
     * @return array|null
     */
    public function getChoices()
    {
        if (null === $this->_choices) {
            $this->setChoices();
        }
        return $this->_choices;
    }

    /**
     * @param int $choiceTypeId
     * @param string $value
     * @return string
     */
    public function getLabel($choiceTypeId, $value)
    {
        $choices = $this->getChoices();
        $subArr = ArrayHelper::search($choices, 'list_type_id', $choiceTypeId, true);
        $subArrV = ArrayHelper::search($subArr, 'value', $value, false);

        return $subArrV['label'] ?? $value;
    }

}