<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2018-12-06 20:32
 * Time: 20:32
 */

namespace common\components;


use backend\modules\auth\Session;
use common\helpers\Utils;
use Yii;

class Settings extends \yii2mod\settings\components\Settings
{
    /**
     * @param null $section
     * @param string $key
     * @param string $value
     * @param null $type
     * @return bool
     */
    public function set($section, $key, $value, $type = null): bool
    {
        $section = static::getQualifiedSection($section);
        return parent::set($section, $key, $value, $type);
    }

    /**
     * @param string $section
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($section, $key, $default = null)
    {
        $qualifiedSection = static::getQualifiedSection($section);
        $val = parent::get($qualifiedSection, $key, $default);
        if ($val === null) {
            $val = parent::get($section, $key, $default);
        }
        return $val;
    }


    /**
     * @param string $section
     * @param null|int $country_id
     * @return string
     */
    public static function getQualifiedSection($section, $country_id = null)
    {
        if (Utils::isWebApp() && !Yii::$app->user->getIsGuest() && Session::isOrganizationRef()) {
            $country_id = Session::getCountryId();
        }
        if (!empty($country_id)) {
            $section .= '_org_' . $country_id;
        }
        return $section;
    }

}