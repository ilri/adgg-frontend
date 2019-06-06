<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_extendable_table".
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property int $is_active
 * @property string $uuid
 * @property string $created_at
 * @property int $created_by
 *
 * @property TableAttributes[] $coreTableAttributes
 */
class ExtendableTable extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_extendable_table}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'label'], 'required'],
            [['is_active', 'created_by'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['label', 'uuid'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'is_active' => Lang::t('Active'),
            'uuid' => Lang::t('Uuid'),
            'created_at' => Lang::t('Created At'),
            'created_by' => Lang::t('Created By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTableAttributes()
    {
        return $this->hasMany(TableAttributes::class, ['table_id' => 'id']);
    }

    /**
     *  {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'is_active',
        ];
    }
}
