<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_master_country".
 * @property int $id
 * @property string $iso2
 * @property string $name
 * @property int $is_active
 * @property string $call_code
 * @property string $currency
 */
class Country extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_master_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso2', 'name'], 'required'],
            [['iso2'], 'string', 'min' => 2, 'max' => 2],
            [['call_code'], 'string', 'max' => 10],
            [['currency'], 'string', 'min' => 3, 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['is_active'], 'integer'],
            [['iso2'], 'unique', 'message' => Lang::t('{attribute} {value} already exists.')],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'iso2' => Lang::t('ISO Code'),
            'name' => Lang::t('Name'),
            'is_active' => Lang::t('Active'),
            'call_code' => Lang::t('Country Code/Dialing Code'),
            'currency' => Lang::t('Currency'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            ['iso2', 'iso2'],
            'is_active',
            'call_code',
            'currency',
        ];
    }

}
