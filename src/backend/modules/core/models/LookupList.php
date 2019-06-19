<?php

namespace backend\modules\core\models;

use common\helpers\DbUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_master_list".
 *
 * @property int $id
 * @property string $value
 * @property string $label
 * @property int $list_type_id
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 * @property int $is_deleted
 * @property string $deleted_at
 * @property int $deleted_by
 *
 * @property ListType $listType
 */
class LookupList extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_master_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'label', 'list_type_id'], 'required'],
            [['list_type_id', 'is_active'], 'integer'],
            [['value'], 'string', 'max' => 128],
            [['label'], 'string', 'max' => 255],
            [['list_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ListType::class, 'targetAttribute' => ['list_type_id' => 'id']],
            ['value', 'unique', 'targetAttribute' => ['list_type_id', 'value'], 'message' => Lang::t('{attribute} already exists.')],
            ['label', 'unique', 'targetAttribute' => ['list_type_id', 'label'], 'message' => Lang::t('{attribute} already exists.')],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'label' => 'Label',
            'list_type_id' => 'List Type',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListType()
    {
        return $this->hasOne(ListType::class, ['id' => 'list_type_id']);
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['value', 'value'],
            ['label', 'label'],
            'list_type_id',
            'is_active',
        ];
    }

    /**
     * @param int $listType
     * @param bool $prompt
     * @param string $condition
     * @param array $params
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function getList($listType, $prompt = false, $condition = '', $params = [], $options = [])
    {
        $options['orderBy'] = ['value' => SORT_ASC];
        list($condition, $params) = DbUtils::appendCondition('list_type_id', $listType, $condition, $params);
        return parent::getListData('value', 'label', $prompt, $condition, $params, $options);
    }

    /**
     * @param mixed $prompt
     * @param string $condition
     * @param array $params
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function getProjectListData($prompt = false, $condition = '', $params = [], $options = [])
    {
        return static::getList(ListType::LIST_TYPE_PROJECT, $prompt, $condition, $params, $options);
    }

    /**
     * @param mixed $prompt
     * @param string $condition
     * @param array $params
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function getFarmTypeListData($prompt = false, $condition = '', $params = [], $options = [])
    {
        return static::getList(ListType::LIST_TYPE_FARM_TYPE, $prompt, $condition, $params, $options);
    }

    /**
     * @param mixed $prompt
     * @param string $condition
     * @param array $params
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function getGenderListData($prompt = false, $condition = '', $params = [], $options = [])
    {
        return static::getList(ListType::LIST_TYPE_PROJECT, $prompt, $condition, $params, $options);
    }
}
