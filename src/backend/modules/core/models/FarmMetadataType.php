<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_farm_metadata_type".
 *
 * @property int $id
 * @property int $code
 * @property string $name
 * @property int $is_active
 * @property int $farmer_has_multiple
 * @property int|null $parent_id
 * @property string $model_class_name
 * @property string $created_at
 * @property int|null $created_by
 *
 * @property FarmMetadata $parent
 * @property FarmMetadata [] $children
 */
class FarmMetadataType extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_farm_metadata_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'model_class_name'], 'required'],
            ['code', 'unique', 'targetAttribute' => ['code'], 'message' => '{attribute} {value} already exists.'],
            [['code', 'is_active', 'farmer_has_multiple', 'parent_id'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['model_class_name'], 'string', 'max' => 255],
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
            'is_active' => 'Is Active',
            'farmer_has_multiple' => 'Farmer Has Multiple',
            'parent_id' => 'Parent ID',
            'model_class_name' => 'Model Class Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function searchParams()
    {
        return [
            ['name', 'name'],
            'code',
            'is_active',
            'model_class_name',
        ];
    }

    public static function getListData($valueColumn = 'code', $textColumn = 'name', $prompt = false, $condition = '', $params = [], $options = [])
    {
        $options['orderBy'] = ['code' => SORT_ASC];
        return parent::getListData($valueColumn, $textColumn, $prompt, $condition, $params, $options);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(static::class, ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(static::class, ['parent_id' => 'id']);
    }
}
