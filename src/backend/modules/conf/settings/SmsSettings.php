<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-12-10 18:44
 * Time: 18:44
 */

namespace backend\modules\conf\settings;


use common\helpers\Lang;

class SmsSettings extends BaseSettings
{
    //sms settings
    const SECTION_SMS = 'sms';
    const KEY_BASE_URL = 'baseUrl';
    const KEY_DEFAULT_SENDER_ID = 'defaultSenderId';
    const KEY_USERNAME = 'username';
    const KEY_PASSWORD = 'password';
    const KEY_API_KEY = 'apiKey';
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var string
     */
    public $defaultSenderId;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $apiKey;

    public function rules()
    {
        return [
            [
                [self::KEY_BASE_URL, self::KEY_DEFAULT_SENDER_ID],
                'required'
            ],
            [self::KEY_BASE_URL, 'url'],
            [
                [self::KEY_USERNAME, self::KEY_PASSWORD, self::KEY_API_KEY],
                'safe'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            self::KEY_BASE_URL => Lang::t('3rd Party API Base URL'),
            self::KEY_DEFAULT_SENDER_ID => Lang::t('Default Sender ID'),
            self::KEY_USERNAME => Lang::t('3rd Party Account Username'),
            self::KEY_PASSWORD => Lang::t('3rd Party Account Password'),
            self::KEY_API_KEY => Lang::t('3rd Party API Key'),
        ];
    }

    /**
     * @return string
     */
    public static function getBaseUrl()
    {
        return static::getSettingsComponent()->get(self::SECTION_SMS, self::KEY_BASE_URL);
    }

    /**
     * @return string
     */
    public static function getDefaultSenderId()
    {
        return static::getSettingsComponent()->get(self::SECTION_SMS, self::KEY_DEFAULT_SENDER_ID);
    }

    /**
     * @return string
     */
    public static function getUsername()
    {
        return static::getSettingsComponent()->get(self::SECTION_SMS, self::KEY_USERNAME);
    }

    /**
     * @return string
     */
    public static function getPassword()
    {
        return static::getSettingsComponent()->get(self::SECTION_SMS, self::KEY_PASSWORD);
    }

    /**
     * @return string
     */
    public static function getApiKey()
    {
        return static::getSettingsComponent()->get(self::SECTION_SMS, self::KEY_API_KEY);
    }

}