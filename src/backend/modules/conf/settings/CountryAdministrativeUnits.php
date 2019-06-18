<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-18
 * Time: 11:31 PM
 */

namespace backend\modules\conf\settings;


use common\helpers\Lang;
use Yii;

class CountryAdministrativeUnits extends BaseSettings
{
    const SECTION_ADMIN_UNITS = 'admin_units';
    const KEY_COUNTRY_UNIT_1 = 'countryUnit1';
    const KEY_COUNTRY_UNIT_2 = 'countryUnit2';
    const KEY_COUNTRY_UNIT_3 = 'countryUnit3';
    const KEY_COUNTRY_UNIT_4 = 'countryUnit4';

    /**
     * @var string
     */
    public $countryUnit1 = 'Region';
    /**
     * @var string
     */
    public $countryUnit2 = 'District';
    /**
     * @var string
     */
    public $countryUnit3 = 'Ward';
    /**
     * @var string
     */
    public $countryUnit4 = 'Village';

    public function rules()
    {
        return [
            [[self::KEY_COUNTRY_UNIT_1, self::KEY_COUNTRY_UNIT_2, self::KEY_COUNTRY_UNIT_3, self::KEY_COUNTRY_UNIT_4], 'required'],
            [[self::KEY_COUNTRY_UNIT_1, self::KEY_COUNTRY_UNIT_2, self::KEY_COUNTRY_UNIT_3, self::KEY_COUNTRY_UNIT_4], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            self::KEY_COUNTRY_UNIT_1 => Lang::t('Default Unit 1 Name (e.g Region)'),
            self::KEY_COUNTRY_UNIT_2 => Lang::t('Default Unit 2 Name (e.g District)'),
            self::KEY_COUNTRY_UNIT_3 => Lang::t('Default Unit 3 Name (e.g Ward)'),
            self::KEY_COUNTRY_UNIT_4 => Lang::t('Default Unit 4 Name (e.g Village)'),
        ];
    }

    /**
     * @return string
     */
    public static function getUnit1Name()
    {
        return static::getSettingsComponent()->get(self::SECTION_ADMIN_UNITS, self::KEY_COUNTRY_UNIT_1, 'Region');
    }

    /**
     * @return string
     */
    public static function getUnit2Name()
    {
        return static::getSettingsComponent()->get(self::SECTION_ADMIN_UNITS, self::KEY_COUNTRY_UNIT_2, 'District');
    }

    /**
     * @return string
     */
    public static function getUnit3Name()
    {
        return static::getSettingsComponent()->get(self::SECTION_ADMIN_UNITS, self::KEY_COUNTRY_UNIT_3, 'Ward');
    }

    /**
     * @return string
     */
    public static function getUnit4Name()
    {
        return static::getSettingsComponent()->get(self::SECTION_ADMIN_UNITS, self::KEY_COUNTRY_UNIT_4, 'Village');
    }
}