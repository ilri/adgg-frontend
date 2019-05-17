<?php

namespace backend\modules\core\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "core_master_currency".
 *
 * @property int $id
 * @property string $iso3
 * @property string $name
 * @property string $symbol
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 */
class Currency extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_master_currency}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iso3', 'name'], 'required'],
            [['iso3'], 'string', 'min' => 3, 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['symbol'], 'string', 'max' => 30],
            [['is_active'], 'integer'],
            [['iso3'], 'unique','message' => Lang::t('{attribute} {value} already exists.')],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('#'),
            'iso3' => Lang::t('ISO Code'),
            'name' => Lang::t('Currency Name'),
            'symbol' => Lang::t('Symbol'),
            'is_active' => Lang::t('Active'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function searchParams()
    {
        return [
            ['iso3', 'iso3'],
            ['name', 'name'],
            'is_active',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getListData($valueColumn = 'iso3', $textColumn = 'iso3', $prompt = false, $condition = '', $params = [], $options = [])
    {
        $options['orderBy'] = ['iso3' => SORT_ASC];
        return parent::getListData($valueColumn, $textColumn, $prompt, $condition, $params, $options);
    }

    /**
     * @param string $iso3Code
     * @param string $attribute
     * @return string
     * @throws \Exception
     */
    public static function getCurrencyAttribute($iso3Code, $attribute)
    {
        $condition = '[[iso3]]=:iso3';
        $params = [':iso3' => $iso3Code];
        return static::getScalar($attribute, $condition, $params);
    }


}
