<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_client".
 *
 * @property int $id Unique id which is auto-generated
 * @property string $name Name of the client e.g ADC
 * @property string|null $description Description of the client
 * @property int $country_id The country of the client
 * @property int|null $org_id The OrganizationRefRef of the client
 * @property int $is_active Whether the record is active
 * @property string|null $additional_attributes
 * @property string $created_at The date the record was created
 * @property int|null $created_by Id of the user who created the records
 *
 * @property Organization $org
 *
 */
class Client extends ActiveRecord implements ActiveSearchInterface, TableAttributeInterface
{
    use ActiveSearchTrait, CountryDataTrait, TableAttributeTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_client}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id', 'org_id', 'is_active'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['additional_attributes'], 'safe'],
            [$this->getAdditionalAttributes(), 'safe'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'country_id' => 'Country',
            'org_id' => 'Organization',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
        return array_merge($labels, $this->getOtherAttributeLabels());
    }

    /**
     * @inheritDoc
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'country_id',
            'org_id',
            'is_active',
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getDefinedTableId(): int
    {
        return ExtendableTable::TABLE_CLIENTS;
    }

    public function getOrg()
    {
        return $this->hasOne(Organization::class, ['id' => 'org_id']);

    }

    /**
     * @inheritDoc
     */
    public static function getDefinedType(): int
    {
        return TableAttribute::TYPE_ATTRIBUTE;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->ignoreAdditionalAttributes = true;
            $this->setAdditionalAttributesValues();

            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->loadAdditionalAttributeValues();
    }
}
