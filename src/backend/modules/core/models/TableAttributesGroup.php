<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_table_attributes_group".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $table_id
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 *
 * @property TableAttribute[] $tableAttributes
 */
class TableAttributesGroup extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_table_attributes_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'table_id'], 'required'],
            [['table_id', 'is_active'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            ['name', 'unique', 'targetAttribute' => ['table_id', 'name'], 'message' => Lang::t('{attribute} already exists.')],
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
            'name' => 'Group Name',
            'description' => 'Description',
            'table_id' => 'Table ID',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTableAttributes()
    {
        return $this->hasMany(TableAttribute::class, ['group_id' => 'id']);
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'table_id',
            'is_active',
        ];
    }
}
