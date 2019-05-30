<?php

namespace backend\modules\core\models;

use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use common\models\ActiveRecord;

/**
 * This is the model class for table "member_registration_document_type".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $business_types
 * @property string $business_entity_types
 * @property int $has_start_date
 * @property int $has_renewal
 * @property int $is_active
 * @property int $created_at
 * @property int $created_by
 *
 * @property RegistrationDocument[] $registrationDocuments
 */
class RegistrationDocumentType extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%org_registration_document_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active', 'has_start_date', 'has_renewal'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            ['name', 'unique'],
            [['business_types', 'business_entity_types'], 'safe'],
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
            'name' => 'Document Name',
            'description' => 'Description',
            'business_types' => 'Business Types',
            'business_entity_types' => 'Business Entities',
            'has_start_date' => 'Has Start Date',
            'has_renewal' => 'Has Renewal',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberRegistrationDocuments()
    {
        return $this->hasMany(RegistrationDocument::class, ['doc_type_id' => 'id']);
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'has_start_date',
            'has_renewal',
            'is_active',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->business_types = serialize($this->business_types);
            $this->business_entity_types = serialize($this->business_entity_types);
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->business_types = unserialize($this->business_types);
        $this->business_entity_types = unserialize($this->business_entity_types);
    }

    /**
     * @return string|null
     */
    public function getFormattedBusinessTypes()
    {
        if (empty($this->business_types)) {
            return null;
        }
        if (is_string($this->business_types)) {
            $this->business_types = unserialize($this->business_types);
        }
        $formattedArr = [];
        foreach ($this->business_types as $b) {
            $formattedArr[] = Organization::decodeBusinessType($b);
        }
        return implode(', ', $formattedArr);
    }

    /**
     * @return string|null
     */
    public function getFormattedBusinessEntityTypes()
    {
        if (empty($this->business_entity_types)) {
            return null;
        }
        if (is_string($this->business_entity_types)) {
            $this->business_entity_types = unserialize($this->business_entity_types);
        }
        $formattedArr = [];
        foreach ($this->business_entity_types as $b) {
            $formattedArr[] = Organization::decodeBusinessEntityType($b);
        }
        return implode(', ', $formattedArr);
    }


    /**
     * @param int|null $businessType
     * @param int|null $businessEntityType
     * @param string $valueColumn
     * @param string $textColumn
     * @param bool $prompt
     * @param string $condition
     * @param array $params
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function getOrgListData($businessType = null, $businessEntityType = null, $valueColumn = 'id', $textColumn = 'name', $prompt = false, $condition = '', $params = [], $options = [])
    {
        $data = parent::getListData($valueColumn, $textColumn, $prompt, $condition, $params, $options);
        if (empty($data))
            return $data;
        $models = static::find()->andWhere(['id' => array_keys($data)])->all();
        foreach ($models as $model) {
            if (!empty($businessType) && !empty($model->business_types)) {
                if (!in_array($businessType, $model->business_types)) {
                    unset($data[$model->id]);
                }
            }

            if (!empty($businessEntityType) && !empty($model->business_entity_types)) {
                if (!in_array($businessEntityType, $model->business_entity_types)) {
                    unset($data[$model->id]);
                }
            }
        }

        return $data;
    }

}
