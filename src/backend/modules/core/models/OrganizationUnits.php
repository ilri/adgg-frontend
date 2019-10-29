<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use yii\helpers\Html;

/**
 * This is the model class for table "organization_units".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $level
 * @property int $org_id
 * @property int $parent_id
 * @property string $contact_name
 * @property string $contact_phone
 * @property string $contact_email
 * @property int $is_active
 * @property string $uuid
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Organization $org
 * @property OrganizationUnits $parent
 */
class OrganizationUnits extends ActiveRecord implements ActiveSearchInterface, UploadExcelInterface
{
    use ActiveSearchTrait;

    const LEVEL_REGION = 1;
    const LEVEL_DISTRICT = 2;
    const LEVEL_WARD = 3;
    const LEVEL_VILLAGE = 4;

    public $parent_code;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization_units}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'level', 'org_id'], 'required'],
            [['level', 'org_id', 'parent_id', 'is_active'], 'integer'],
            [['code', 'contact_name'], 'string', 'max' => 128],
            [['name', 'contact_email'], 'string', 'max' => 255],
            [['contact_phone'], 'string', 'min' => 8, 'max' => 20],
            ['code', 'unique', 'targetAttribute' => ['org_id', 'level', 'code'], 'message' => Lang::t('{attribute} already exists.')],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_id' => 'id']],
            ['contact_email', 'email'],
            ['parent_id', 'required', 'when' => function (self $model) {
                return $model->level != self::LEVEL_REGION;
            }],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPLOAD] = ['code', 'name', 'parent_code'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $labels = [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'level' => 'Level',
            'org_id' => 'Country',
            'contact_name' => 'Contact Name',
            'contact_phone' => 'Contact Phone',
            'contact_email' => 'Contact Email',
            'is_active' => 'Is Active',
            'uuid' => 'Uuid',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];

        $parentIdLabel = 'Parent';
        if ($this->org !== null) {
            switch ($this->level) {
                case self::LEVEL_DISTRICT:
                    $parentIdLabel = Html::encode($this->org->unit1_name);
                    break;
                case self::LEVEL_WARD:
                    $parentIdLabel = Html::encode($this->org->unit2_name);
                    break;
                case self::LEVEL_VILLAGE:
                    $parentIdLabel = Html::encode($this->org->unit3_name);
                    break;
            }
        }
        $labels['parent_id'] = $parentIdLabel;
        $labels['parent_code'] = $parentIdLabel . ' Code';

        return $labels;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organization::class, ['id' => 'org_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(OrganizationUnits::class, ['id' => 'parent_id']);
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['code', 'code'],
            ['name', 'name'],
            'level',
            'org_id',
            'parent_id',
            'is_active',
        ];
    }

    /**
     * @return array
     */
    public function getExcelColumns()
    {
        $columns = [
            'code',
            'name',
            'parent_code',
        ];

        return $columns;
    }
}
