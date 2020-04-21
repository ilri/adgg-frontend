<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_farm_metadata".
 *
 * @property int $id
 * @property int $farm_id
 * @property int $type
 * @property string|array|null $additional_attributes
 * @property string $created_at
 * @property int|null $created_by
 *
 * @property Farm $farm
 */
class FarmMetadata extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface
{
    use ActiveSearchTrait, TableAttributeTrait;

    //types
    const TYPE_FEEDING_TECHNIQUE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_farm_metadata}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['farm_id', 'type'], 'required'],
            [['farm_id', 'type'], 'integer'],
            [['additional_attributes'], 'safe'],
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
            'farm_id' => 'Farm ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFarm()
    {
        return $this->hasOne(Farm::class, ['id' => 'farm_id']);
    }

    public function searchParams()
    {
        return [
            'farm_id',
            'type',
        ];
    }

    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_FARM_METADATA;
    }
}
