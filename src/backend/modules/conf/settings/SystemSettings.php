<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-12-06 19:49
 * Time: 19:49
 */

namespace backend\modules\conf\settings;


use common\helpers\Lang;
use Yii;

class SystemSettings extends BaseSettings
{
    const SECTION_SYSTEM = 'system';
    const KEY_APP_NAME = 'appName';
    const KEY_COMPANY_NAME = 'companyName';
    const KEY_COMPANY_EMAIL = 'companyEmail';
    const KEY_DEFAULT_CURRENCY = 'defaultCurrency';
    const KEY_DEFAULT_TIMEZONE = 'defaultTimezone';
    const KEY_DEFAULT_COUNTRY = 'defaultCountry';
    const KEY_PAGINATION_SIZE = 'paginationSize';
    const KEY_DEFAULT_THEME = 'defaultTheme';
    /**
     *
     * @var string
     */
    public $companyName;
    /**
     *
     * @var string
     */
    public $appName;

    /**
     *
     * @var string
     */
    public $companyEmail;

    /**
     *
     * @var string
     */
    public $defaultTimezone;

    /**
     *
     * @var integer
     */
    public $defaultCountry;

    /**
     *
     * @var string
     */
    public $defaultCurrency;

    /**
     *
     * @var integer
     */
    public $paginationSize;

    /**
     * @var string
     */
    public $defaultTheme;

    //themes
    const THEME_DEFAULT = 'default';
    const THEME_GREEN = 'green';
    const THEME_DARK = 'dark';

    public function rules()
    {
        return [
            [
                [
                    self::KEY_APP_NAME,
                    self::KEY_COMPANY_NAME,
                    self::KEY_DEFAULT_TIMEZONE,
                    self::KEY_DEFAULT_COUNTRY,
                    self::KEY_PAGINATION_SIZE,
                    self::KEY_DEFAULT_THEME,
                ],
                'required',
            ],
            [
                [self::KEY_COMPANY_NAME, self::KEY_APP_NAME], 'safe',
            ],
            [[self::KEY_PAGINATION_SIZE], 'integer'],
            [self::KEY_DEFAULT_CURRENCY, 'string', 'min' => 3, 'max' => 3],
            [[self::KEY_COMPANY_EMAIL], 'email', 'message' => 'Enter a valid Email Address.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            self::KEY_COMPANY_NAME => Lang::t('Company Name'),
            self::KEY_APP_NAME => Lang::t('App Name'),
            self::KEY_COMPANY_EMAIL => Lang::t('Email'),
            self::KEY_DEFAULT_TIMEZONE => Lang::t('Default Timezone'),
            self::KEY_DEFAULT_COUNTRY => Lang::t('Country'),
            self::KEY_PAGINATION_SIZE => Lang::t('Items Per Page'),
            self::KEY_DEFAULT_THEME => Lang::t('Theme'),
            self::KEY_DEFAULT_CURRENCY => Lang::t('Default Currency'),
        ];
    }

    /**
     * @return string
     */
    public static function getCompanyName()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_COMPANY_NAME, Yii::$app->name);
    }

    /**
     * @return string
     */
    public static function getAppName()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_APP_NAME, Yii::$app->name);
    }

    /**
     * @return string
     */
    public static function getCompanyEmail()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_COMPANY_EMAIL);
    }

    /**
     * @return string
     */
    public static function getDefaultTimezone()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_DEFAULT_TIMEZONE, date_default_timezone_get());
    }

    /**
     * @return string
     */
    public static function getDefaultCountry()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_DEFAULT_COUNTRY);
    }

    /**
     * @return string
     */
    public static function getDefaultCurrency()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_DEFAULT_CURRENCY, 'KES');
    }

    /**
     * @return string
     */
    public static function getPaginationSize()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_PAGINATION_SIZE, 50);
    }

    /**
     * @return string
     */
    public static function getDefaultTheme()
    {
        return static::getSettingsComponent()->get(self::SECTION_SYSTEM, self::KEY_DEFAULT_THEME, self::THEME_GREEN);
    }

    /**
     * @param mixed $val
     * @return null|string
     */
    public static function decodeTheme($val)
    {
        $stringVal = null;
        switch ($val) {
            case self::THEME_DEFAULT:
                $stringVal = 'Default Skin';
                break;
            case self::THEME_GREEN:
                $stringVal = 'Green Skin';
                break;
            case self::THEME_DARK:
                $stringVal = 'Dark Skin';
                break;
        }

        return $stringVal;
    }

    /**
     * @return array
     */
    public static function themeOptions()
    {
        return [
            self::THEME_DEFAULT => static::decodeTheme(self::THEME_DEFAULT),
            //self::THEME_GREEN => static::decodeTheme(self::THEME_GREEN),
            //self::THEME_DARK => static::decodeTheme(self::THEME_DARK),
        ];
    }
}