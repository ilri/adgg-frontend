<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-10-05
 * Time: 8:01 PM
 */

namespace backend\modules\core\forms;


use backend\modules\core\models\ExcelImport;
use backend\modules\core\models\AnimalUpdate;
use common\excel\ExcelUploadForm;
use common\excel\ImportInterface;
use Yii;

class UpdateAnimals extends ExcelUploadForm implements ImportInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge($this->excelValidationRules(), [
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge($this->excelAttributeLabels(), [
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
        foreach ($batch as $k => $excel_row) {
            $row = $this->getExcelRowColumns($excel_row, $columns);
            if (empty($row)) {
                continue;
            }
            $row['birthdate'] = static::getDateColumnData($row['birthdate'] ?? null);
            $insert_data[$k] = $row;
        }
        $this->saveModels($insert_data);
    }

    /**
     * @return string|int
     */
    public function setUploadType()
    {
        $this->_uploadType = ExcelImport::TYPE_UPDATE_ANIMAL_DATA;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function saveModels(array $data)
    {
        if (empty($data)) {
            return false;
        }

        $nMax = 0;
        foreach ($data as $n => $row) {
            $tagId = $row['tag_id'];
            $model = $this->getAnimalModel($tagId);
            if (null === $model) {
                Yii::$app->controller->stdout("Row {$n} update failed. Animal Tag Id ($tagId) does not exist.\n");
                continue;
            }
            $this->saveExcelRow($model, $row, $n);
            $nMax = $n;
        }

        $this->updateCurrentProcessedRow($nMax);
    }

    /**
     * @param $tagId
     * @return array|AnimalUpdate|\yii\db\ActiveRecord|null
     */
    protected function getAnimalModel($tagId)
    {
        $tagId = trim($tagId);
        return AnimalUpdate::find()->andWhere(['tag_id' => $tagId])->one();
    }
}