<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;

/**
 * This is the model class for table "core_repeat_type".
 *
 * @property int $id
 * @property string $name
 * @property int $code
 * @property string $belongs_to
 * @property string $created_at
 * @property int $created_by
 */
class RepeatType extends ActiveRecord implements ActiveSearchInterface
{
    const BELONGS_TO_USER = 'User';
    const BELONGS_TO_FARMER = 'Farmer';
    const BELONGS_TO_ANIMAL = 'Animal';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_repeat_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code', 'belongs_to'], 'required'],
            [['code', 'belongs_to'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['belongs_to'], 'string', 'max' => 30],
            [['code'], 'unique'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'belongs_to' => 'Belongs To',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @inheritDoc
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            ['code', 'code'],
            'belongs_to',
        ];
    }
}
