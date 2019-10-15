<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-09-04
 * Time: 9:41 AM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\Animal;
use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\Farm;
use backend\modules\core\models\ListType;
use backend\modules\core\models\LookupList;
use backend\modules\core\models\Organization;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use common\helpers\Lang;
use console\jobs\JobInterface;
use console\jobs\JobTrait;
use Yii;

class UploadAnimals extends ExcelUploadForm implements ImportInterface, JobInterface
{
    use JobTrait;
    /**
     * @var int
     */
    public $org_id;

    /**
     * @var Organization
     */
    public $orgModel;

    /**
     * @var int
     */
    public $itemId;

    /**
     * @var string
     */
    public $type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
            [['org_id', 'type'], 'required'],
            [['type'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
            'org_id' => Lang::t('Country'),
            'type' => Lang::t('Type'),
        ]);
    }

    /**
     * @param $batch
     * @return mixed
     * @throws \Exception
     */
    public function processExcelBatchData($batch)
    {
        $columns = [];
        $insert_data = [];
        $this->orgModel = Organization::loadModel($this->org_id);

        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            if (!empty($row['farm_id'])) {
                $row['farm_id'] = $this->getFarmId($row['farm_id']);
            }

            $row['derived_birthdate'] = static::getDateColumnData($row['derived_birthdate'] ?? null);
            $row['birthdate'] = static::getDateColumnData($row['birthdate'] ?? null);
            $row['entry_date'] = static::getDateColumnData($row['entry_date'] ?? null);


            if (!empty($row['deformities'])) {
                $row['deformities'] = array_map('trim', explode(' ', $row['deformities']));
            }
            try {
                $row['main_breed'] = $this->getBreedId($row['main_breed'] ?? null);
                $row['secondary_breed'] = $this->getBreedId($row['secondary_breed'] ?? null);
            } catch (\Exception $e) {
                Yii::info($e->getMessage());
            }
            $insert_data[$k] = $row;
        }

        $this->save($insert_data);
    }

    /**
     * @param string $farmCode
     * @return bool|string
     * @throws \Exception
     */
    protected function getFarmId($farmCode)
    {
        $farmCode = trim($farmCode);
        $farmCode = ltrim($farmCode, '0');
        $condition = '([[code]]=:code1 OR [[code]]=:code2)';
        $params = [':code1' => $farmCode, ':code2' => '0' . $farmCode];
        $farmId = Farm::getScalar('id', $condition, $params);

        if ($farmId) {
            return $farmId;
        }
        return null;
    }

    /**
     * @param $name
     * @return string
     * @throws \Exception
     */
    protected function getBreedId($name)
    {
        $name = trim($name);
        if (empty($name)) {
            return null;
        }
        //ListType::get
        $value = LookupList::getScalar('value', ['list_type_id' => ListType::LIST_TYPE_ANIMAL_BREEDS, 'label' => $name]);
        if (!$value) {
            $nextValue = (int)LookupList::getScalar('max([[value]])', ['list_type_id' => ListType::LIST_TYPE_ANIMAL_BREEDS]);
            $nextValue += 1;
            $model = new LookupList(['value' => $nextValue, 'label' => $name, 'list_type_id' => ListType::LIST_TYPE_ANIMAL_BREEDS]);
            $model->save(false);
            return $model->value;
        }

        return $value;
    }


    /**
     * @param array $data
     * @return bool
     */
    public function save($data)
    {
        if (empty($data))
            return false;

        $model = new Animal(['org_id' => $this->org_id, 'type' => $this->type]);
        foreach ($data as $n => $row) {
            $newModel = Animal::find()->andWhere([
                'tag_id' => $row['tag_id'],
            ])->one();

            if (null === $newModel) {
                $newModel = clone $model;
            }
            $newModel->setScenario(Animal::SCENARIO_UPLOAD);
            $this->saveExcelRaw($newModel, $row, $n);
        }

        if (!empty($this->getFailedRows())) {
            foreach ($this->getFailedRows() as $log) {
                Yii::warning($log);
            }
        }
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
       $this->_uploadType=ExcelImport::TYPE_ANIMAL_DATA;
    }
}