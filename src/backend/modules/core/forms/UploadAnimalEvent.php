<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 11:03 AM
 */

namespace backend\modules\core\forms;


use backend\modules\auth\models\Users;
use backend\modules\core\models\Animal;
use common\excel\ExcelUploadForm;
use common\helpers\Lang;

abstract class UploadAnimalEvent extends ExcelUploadForm
{

    /**
     * @var int
     */
    public $country_id;

    /**
     * @var int
     */
    public $event_type;

    /**
     * @var string
     */
    public $sampleExcelFileName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
            [['country_id', 'event_type', 'sampleExcelFileName'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'country_id' => Lang::t('Country'),
            'event_type' => Lang::t('Event Type'),
        ]);
    }

    protected function getAnimalId($tagId)
    {
        $tagId = trim($tagId);
        $animalId = Animal::getScalar('id', ['country_id' => $this->country_id, 'tag_id' => $tagId]);
        if (empty($animalId)) {
            return null;
        }
        return $animalId;
    }

    /**
     * @param array $row
     * @return array
     * @throws \Exception
     */
    protected function setDefaultAttributes(array $row)
    {
        $row['country_id'] = $this->country_id;
        $row['event_date'] = static::getDateColumnData($row['event_date']);
        $row['animal_id'] = $this->getAnimalId($row['animalTagId']);
        return $row;
    }

    protected function getFieldAgentId($code)
    {
        $userId = Users::getScalar('id', ['username' => $code]);
        if (empty($userId)) {
            return null;
        }
        return $userId;
    }
}