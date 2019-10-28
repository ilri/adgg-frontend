<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 11:03 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\Animal;
use common\excel\ExcelUploadForm;
use common\helpers\Lang;

abstract class UploadAnimalEvent extends ExcelUploadForm
{

    /**
     * @var int
     */
    public $org_id;

    /**
     * @var int
     */
    public $event_type;

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
            [['org_id', 'event_type'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'org_id' => Lang::t('Country'),
            'event_type' => Lang::t('Event Type'),
        ]);
    }

    protected function getAnimalId($tagId)
    {
        $tagId = trim($tagId);
        $animalId = Animal::getScalar('id', ['org_id' => $this->org_id, 'tag_id' => $tagId]);
        if (empty($animalId)) {
            return null;
        }
        return $animalId;
    }
}