<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use common\helpers\Lang;
use common\helpers\Utils;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\base\InvalidArgumentException;

/**
 * This is the model class for table "core_table_attribute".
 *
 * @property int $id
 * @property string $attribute_key
 * @property string $attribute_label
 * @property int $table_id
 * @property int $group_id
 * @property int $input_type
 * @property string $default_value
 * @property int $list_type_id
 * @property int $type
 * @property int $event_type
 * @property int $is_active
 * @property int $is_alias
 * @property string $alias_to
 * @property string $created_at
 * @property int $created_by
 *
 * @property TableAttributesGroup $group
 * @property ListType $listType
 */
class TableAttribute extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    //input types
    const INPUT_TYPE_TEXT = 1;
    const INPUT_TYPE_NUMBER = 2;
    const INPUT_TYPE_EMAIL = 3;
    const INPUT_TYPE_CHECKBOX = 4;
    const INPUT_TYPE_SELECT = 5;
    const INPUT_TYPE_MULTI_SELECT = 6;
    const INPUT_TYPE_TEXTAREA = 7;
    const INPUT_TYPE_DATE = 8;
    //type
    const TYPE_ATTRIBUTE = 1;
    const TYPE_EVENT = 2;


    public function init()
    {
        parent::init();
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_table_attribute}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_key', 'attribute_label', 'table_id', 'input_type'], 'required'],
            [['table_id', 'group_id', 'input_type', 'list_type_id', 'is_active', 'type', 'event_type', 'is_alias'], 'integer'],
            [['default_value'], 'string'],
            [['attribute_key', 'alias_to'], 'string', 'max' => 128],
            [['attribute_label'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => TableAttributesGroup::class, 'targetAttribute' => ['group_id' => 'id']],
            ['attribute_key', 'unique', 'targetAttribute' => ['table_id', 'attribute_key'], 'message' => Lang::t('{attribute} already exists.')],
            ['list_type_id', 'required', 'when' => function (self $model) {
                return $model->input_type == self::INPUT_TYPE_SELECT;
            }],
            ['attribute_key', 'validateAttributeKey'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    public function validateAttributeKey()
    {
        if ($this->hasErrors()) {
            return false;
        }
        if (!preg_match("/^[A-Za-z0-9_]*$/", $this->attribute_key)) {
            $this->addError('attribute_key', Lang::t('{attribute_key_label} must only have alphanumeric characters and underscore.', [
                'attribute_key_label' => $this->getAttributeLabel('attribute_key'),
            ]));
            return false;
        }
        $parentModel = null;
        switch ($this->table_id) {
            case ExtendableTable::TABLE_CLIENT:
                $parentModel = new Client();
                break;
            case ExtendableTable::TABLE_FARM:
                $parentModel = new Farm();
                break;
            case ExtendableTable::TABLE_ANIMAL_ATTRIBUTES:
                $parentModel = new Animal();
                break;
            case ExtendableTable::TABLE_ANIMAL_EVENTS:
                $parentModel = new AnimalEvent();
                break;
            case ExtendableTable::TABLE_ANIMAL_REPEATS:
                //@todo add table
                break;
            case ExtendableTable::TABLE_FARM_REPEATS:
                //@todo add table
                break;
            case ExtendableTable::TABLE_CLIENT_REPEATS:
                //@todo add table
                break;
            case ExtendableTable::TABLE_USERS:
                $parentModel = new Users();
                break;
        }

        $parentModel->ignoreAdditionalAttributes = true;
        if ($parentModel !== null && $parentModel->hasAttribute($this->attribute_key)) {
            $this->addError('attribute_key', Lang::t('{attribute_key_label} already defined in the primary table.', [
                'attribute_key_label' => $this->getAttributeLabel('attribute_key'),
            ]));
        }
        $parentModel->ignoreAdditionalAttributes = false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attribute_key' => 'Attribute Key',
            'attribute_label' => 'Attribute Label',
            'table_id' => 'Table',
            'group_id' => 'Group',
            'input_type' => 'Input Type',
            'list_type_id' => 'List Type',
            'default_value' => 'Default Value',
            'type' => 'Type',
            'event_type' => 'Animal Event Type',
            'is_active' => 'Active',
            'is_alias' => 'Is Alias',
            'alias_to' => 'Alias to',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(TableAttributesGroup::class, ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListType()
    {
        return $this->hasOne(ListType::class, ['id' => 'list_type_id']);
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['attribute_key', 'attribute_key'],
            ['attribute_label', 'attribute_label'],
            'table_id',
            'group_id',
            'type',
            'event_type',
            'is_active',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setDefaultValues();
            $this->default_value = serialize($this->default_value);
            if (!in_array($this->input_type, [self::INPUT_TYPE_SELECT, self::INPUT_TYPE_MULTI_SELECT])) {
                $this->list_type_id = null;
            }
            return true;
        }
        return false;
    }

    public function setDefaultValues()
    {
        if (empty($this->type)) {
            $eventTables = [
                ExtendableTable::TABLE_ANIMAL_EVENTS,
            ];
            if (in_array($this->table_id, $eventTables)) {
                $this->type = self::TYPE_EVENT;
            } else {
                $this->type = self::TYPE_ATTRIBUTE;
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->default_value = unserialize($this->default_value);
    }

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeInputType($intVal)
    {
        switch ($intVal) {
            case self::INPUT_TYPE_TEXT:
                return 'TEXT';
            case self::INPUT_TYPE_NUMBER:
                return 'NUMBER';
            case self::INPUT_TYPE_EMAIL:
                return 'EMAIL';
            case self::INPUT_TYPE_CHECKBOX:
                return 'CHECKBOX';
            case self::INPUT_TYPE_SELECT:
                return 'SELECT';
            case self::INPUT_TYPE_MULTI_SELECT:
                return 'MULTI SELECT';
            case self::INPUT_TYPE_TEXTAREA:
                return 'TEXTAREA';
            case self::INPUT_TYPE_DATE:
                return 'DATE';
            default:
                throw new InvalidArgumentException();
        }
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function inputTypeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::INPUT_TYPE_TEXT => static::decodeInputType(self::INPUT_TYPE_TEXT),
            self::INPUT_TYPE_NUMBER => static::decodeInputType(self::INPUT_TYPE_NUMBER),
            self::INPUT_TYPE_EMAIL => static::decodeInputType(self::INPUT_TYPE_EMAIL),
            self::INPUT_TYPE_DATE => static::decodeInputType(self::INPUT_TYPE_DATE),
            self::INPUT_TYPE_CHECKBOX => static::decodeInputType(self::INPUT_TYPE_CHECKBOX),
            self::INPUT_TYPE_SELECT => static::decodeInputType(self::INPUT_TYPE_SELECT),
            self::INPUT_TYPE_MULTI_SELECT => static::decodeInputType(self::INPUT_TYPE_MULTI_SELECT),
            self::INPUT_TYPE_TEXTAREA => static::decodeInputType(self::INPUT_TYPE_TEXTAREA),
        ], $prompt);
    }

    /**
     * @return string
     */
    public function getDecodedInputType()
    {
        return static::decodeInputType($this->input_type);
    }

    /**
     * @param int $table_id
     * @return array|TableAttribute[]|\yii\db\ActiveRecord[]
     */
    public static function getTableAttributes($table_id)
    {
        return static::find()->andWhere(['table_id' => $table_id, 'is_active' => 1])->orderBy(['group_id' => SORT_ASC, 'id' => SORT_ASC])->all();
    }

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeType($intVal)
    {
        switch ($intVal) {
            case self::TYPE_ATTRIBUTE:
                return 'ATTRIBUTE';
            case self::TYPE_EVENT:
                return 'EVENT';
            default:
                throw new InvalidArgumentException();
        }
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function typeOptions($prompt = false)
    {
        return Utils::appendDropDownListPrompt([
            self::TYPE_ATTRIBUTE => static::decodeType(self::TYPE_ATTRIBUTE),
            self::TYPE_EVENT => static::decodeType(self::TYPE_EVENT),
        ], $prompt);
    }

    /**
     * @param int $tableId
     * @param int $type
     * @return mixed
     * @throws \Exception
     */
    public static function getDefinedAttributes($tableId, $type)
    {
        return static::getData(['id', 'attribute_key','input_type'], ['table_id' => $tableId, 'type' => $type, 'is_active' => 1]);
    }

    /**
     * @return array
     */
    public function getAliasToList()
    {
        $model = null;
        switch ($this->table_id) {
            case ExtendableTable::TABLE_CLIENT:
                $model = new Client();
                break;
            case ExtendableTable::TABLE_FARM:
                $model = new Farm();
                break;
            case ExtendableTable::TABLE_ANIMAL_ATTRIBUTES:
                $model = new Animal();
                break;
            case ExtendableTable::TABLE_ANIMAL_EVENTS:
                $model = new AnimalEvent();
                break;
            case ExtendableTable::TABLE_ANIMAL_REPEATS:
                //@todo add table
                break;
            case ExtendableTable::TABLE_FARM_REPEATS:
                //@todo add table
                break;
            case ExtendableTable::TABLE_CLIENT_REPEATS:
                //@todo add table
                break;
            case ExtendableTable::TABLE_USERS:
                $model = new Users();
                break;
        }

        if (null === $model) {
            return [];
        }
        return $model->getOriginalAttributesListData();
    }


}
