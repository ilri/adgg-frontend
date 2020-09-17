<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_animal_breed_group".
 *
 * @property int $id
 * @property string $name
 * @property string|array $breeds
 * @property int $is_active
 * @property string $created_at
 * @property int|null $created_by
 */
class AnimalBreedGroup extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_animal_breed_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'breeds'], 'required'],
            [['breeds'], 'safe'],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 128],
            ['name', 'unique'],
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
            'name' => 'Breed Group Name',
            'breeds' => 'Breeds',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function searchParams()
    {
        return [
            ['name', 'name'],
            'is_active',
        ];
    }

    /**
     * @param mixed $prompt
     * @return array
     * @throws \Exception
     */
    public static function breedsList($prompt = false)
    {
        return Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, $prompt);
    }
}
