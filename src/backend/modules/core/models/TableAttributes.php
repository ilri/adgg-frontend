<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_table_attributes".
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property int $table_id
 * @property int $data_type
 * @property int $min_length
 * @property int $max_length
 * @property int $allow_null
 * @property string $default_value
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 * @property int $is_deleted
 * @property string $deleted_at
 * @property int $deleted_by
 *
 * @property ExtendableTable $table
 */
class TableAttributes extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    //data types

    const DATA_TYPE_INT = 1;
    const DATA_TYPE_FLOAT = 2;
    const DATA_TYPE_STRING = 3;
    const DATA_TYPE_DATE = 4;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_table_attributes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'label', 'table_id', 'data_type'], 'required'],
            [['table_id', 'data_type', 'min_length', 'max_length', 'allow_null', 'is_active'], 'integer'],
            [['default_value'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['label'], 'string', 'max' => 255],
            [
                ['name'],
                function ($attribute, $params) {
                    //returns true / false (preg_replace returns the string with replaced matched regex)
                    if (preg_match('/\s+/', $this->attribute)) {
                        $this->addError($attribute, 'No white spaces allowed!');
                    }
                },
            ],
            ['name', 'unique', 'targetAttribute' => ['table_id', 'name'], 'message' => Lang::t('{attribute} already exists.')],
            [['table_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExtendableTable::class, 'targetAttribute' => ['table_id' => 'id']],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('ID'),
            'name' => Lang::t('Name'),
            'label' => Lang::t('Label'),
            'table_id' => Lang::t('Table'),
            'data_type' => Lang::t('Data Type'),
            'min_length' => Lang::t('Min Length'),
            'max_length' => Lang::t('Max Length'),
            'allow_null' => Lang::t('Allow Null'),
            'default_value' => Lang::t('Default Value'),
            'is_active' => Lang::t('Active'),
            'created_at' => Lang::t('Created At'),
            'created_by' => Lang::t('Created By'),
        ];
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'table_id',
            'is_active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTable()
    {
        return $this->hasOne(ExtendableTable::class, ['id' => 'table_id']);
    }
}
