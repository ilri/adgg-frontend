<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-08-27
 * Time: 10:21 PM
 */

namespace common\excel;


use backend\modules\core\models\Product;
use common\models\ActiveRecord;
use common\models\Model;
use yii\base\InvalidConfigException;

class ExcelUploadForm extends Model
{
    use ExcelReaderTrait;

    /**
     * @var string|ActiveRecord
     */
    public $activeRecordModelClass;

    /**
     * @var ActiveRecord|ImportActiveRecordInterface
     */
    private $_model;

    /**
     * UploadProductExcel constructor.
     * @param string $activeRecordModelClass
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(string $activeRecordModelClass, $config = [])
    {
        if (empty($activeRecordModelClass)) {
            throw new InvalidConfigException();
        }
        $config['activeRecordModelClass'] = $activeRecordModelClass;
        parent::__construct($config);

        $this->required_columns = [];
        $className = $this->activeRecordModelClass;
        $this->_model = new $className();
        foreach ($this->_model->getExcelColumns() as $column) {
            $this->file_columns['[' . $column . ']'] = $this->_model->getAttributeLabel($column);
        }
        $this->end_column = static::numberToExcelColumn(count($this->file_columns), true);
    }
}