<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_master_county".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $country
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 */
class County extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_master_county}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['is_active'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 3],
            [['code', 'name'], 'unique'],
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
            'code' => 'Code',
            'name' => 'Name',
            'country' => 'Country',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['code', 'code'],
            ['name', 'name'],
            'country',
            'is_active',
        ];
    }
}
