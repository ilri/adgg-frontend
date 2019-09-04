<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_animal_herd".
 *
 * @property int $id
 * @property string $name
 * @property string $herd_id
 * @property int $farm_id
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Farm $farm
 */
class AnimalHerd extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_animal_herd}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['herd_id', 'farm_id'], 'required'],
            [['farm_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['herd_id'], 'string', 'max' => 128],
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
            'name' => 'Name',
            'herd_id' => 'Herd ID',
            'farm_id' => 'Farm ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            ['herd_id', 'herd_id'],
            'farm_id',
        ];
    }
}
