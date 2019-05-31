<?php

namespace backend\modules\core\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use Yii;

/**
 * This is the model class for table "country_units".
 *
 * @property int $id
 * @property string $account_no
 * @property string $name
 * @property int $type
 * @property int $org_id
 * @property int $parent_id
 * @property string $street
 * @property string $town
 * @property string $postal_address
 * @property string $contact_first_name
 * @property string $contact_middle_name
 * @property string $contact_last_name
 * @property string $contact_title
 * @property string $contact_phone
 * @property string $contact_alt_phone
 * @property string $contact_email
 * @property string $map_address
 * @property string $map_latitude
 * @property string $map_longitude
 * @property string $uuid
 * @property string $last_login_date
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 */
class CountryUnits extends ActiveRecord implements ActiveSearchInterface
{
    use OrganizationDataTrait, ActiveSearchTrait;

    const TYPE_REGION = 1;
    const TYPE_DISTRICT = 2;
    const TYPE_WARD = 3;
    const TYPE_VILLAGE = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%country_units}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_no', 'name', 'type', 'contact_first_name', 'contact_last_name', 'contact_phone', 'contact_email'], 'required'],
            [['type', 'org_id', 'parent_id', 'created_by', 'updated_by'], 'integer'],
            [['map_latitude', 'map_longitude'], 'number'],
            [['last_login_date', 'created_at', 'updated_at'], 'safe'],
            [['account_no', 'contact_first_name', 'contact_middle_name', 'contact_last_name'], 'string', 'max' => 128],
            [['name', 'street', 'town', 'postal_address', 'contact_email', 'map_address', 'uuid'], 'string', 'max' => 255],
            [['contact_title'], 'string', 'max' => 30],
            [['contact_phone', 'contact_alt_phone'], 'string', 'max' => 20],
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
            'account_no' => 'Account No',
            'name' => 'Name',
            'type' => 'Type',
            'org_id' => 'Org ID',
            'parent_id' => 'Parent ID',
            'street' => 'Street',
            'town' => 'Town',
            'postal_address' => 'Postal Address',
            'contact_first_name' => 'Contact First Name',
            'contact_middle_name' => 'Contact Middle Name',
            'contact_last_name' => 'Contact Last Name',
            'contact_title' => 'Contact Title',
            'contact_phone' => 'Contact Phone',
            'contact_alt_phone' => 'Contact Alt Phone',
            'contact_email' => 'Contact Email',
            'map_address' => 'Map Address',
            'map_latitude' => 'Map Latitude',
            'map_longitude' => 'Map Longitude',
            'uuid' => 'Uuid',
            'last_login_date' => 'Last Login Date',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            'type',
        ];
    }
}
