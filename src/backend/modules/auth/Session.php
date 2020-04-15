<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/06/21
 * Time: 7:47 PM
 */

namespace backend\modules\auth;


use backend\modules\auth\models\Roles;
use backend\modules\auth\models\UserLevels;
use Yii;

class Session
{

    /**
     * Returns true if the currently logged in user is dev
     * @return bool
     */
    public static function isDev()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_DEV;
    }

    /**
     * Returns true if the currently logged in user is superadmin/data manager
     * @return bool
     */
    public static function isSuperAdmin()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_SUPER_ADMIN;
    }

    /**
     * Returns true if the currently logged in user is superadmin/data manager
     * @return bool
     */
    public static function isSystemAdmin()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_ADMIN;
    }

    /**
     * @return bool
     */
    public static function isCountry()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        $countryUserLevels = [
            UserLevels::LEVEL_COUNTRY,
            UserLevels::LEVEL_REGION,
            UserLevels::LEVEL_DISTRICT,
            UserLevels::LEVEL_WARD,
            UserLevels::LEVEL_VILLAGE,
            UserLevels::LEVEL_FARMER,
            UserLevels::LEVEL_ORGANIZATION,
            UserLevels::LEVEL_ORGANIZATION_CLIENT,

        ];
        return in_array(Yii::$app->user->identity->level_id, $countryUserLevels);
    }

    public static function isCountryUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_COUNTRY;
    }

    public static function isOrganizationUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_ORGANIZATION;
    }

    public static function isOrganizationClientUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_ORGANIZATION_CLIENT;
    }

    public static function isRegionUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_REGION;
    }

    public static function isDistrictUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_DISTRICT;
    }

    public static function isWardUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_WARD;
    }

    public static function isVillageUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_VILLAGE;
    }

    /**
     * @return bool
     */
    public static function isPrivilegedAdmin()
    {
        return static::isDev() || static::isSuperAdmin() || static::isSystemAdmin();
    }

    /**
     * @return bool
     */
    public static function isFieldAgent()
    {
        $userRole = Yii::$app->user->identity->role_id;
        if ($userRole !== null) {
            $model = Roles::findOne(['id' => $userRole]);
            return $model->isFieldAgent();
        }
        return false;
    }

    /**
     * @param null|string|int $default
     * @return mixed
     */
    public static function getCountryId($default = null)
    {
        if (static::isCountry()) {
            return Yii::$app->user->identity->country_id ?? null;
        }
        return $default;
    }

    /**
     * @param null|string|int $default
     * @return mixed
     */
    public static function getOrgId($default = null)
    {
        if (static::isCountry()) {
            return Yii::$app->user->identity->org_id ?? null;
        }
        return $default;
    }

    public static function getClientId($default = null)
    {
        if (static::isCountry()) {
            return Yii::$app->user->identity->client_id ?? null;
        }
        return $default;
    }

    /**
     * @param null|string|int $default
     * @return mixed
     */
    public static function getRegionId($default = null)
    {
        if (static::isRegionUser()) {
            return Yii::$app->user->identity->region_id ?? null;
        }
        return $default;
    }

    /**
     * @param null|string|int $default
     * @return mixed
     */
    public static function getDistrictId($default = null)
    {
        if (static::isDistrictUser()) {
            return Yii::$app->user->identity->district_id ?? null;
        }
        return $default;
    }

    /**
     * @param null|string|int $default
     * @return mixed
     */
    public static function getWardId($default = null)
    {
        if (static::isWardUser()) {
            return Yii::$app->user->identity->ward_id ?? null;
        }
        return $default;
    }

    /**
     * @param null|string|int $default
     * @return mixed
     */
    public static function getVillageId($default = null)
    {
        if (static::isVillageUser()) {
            return Yii::$app->user->identity->village_id ?? null;
        }
        return $default;
    }

    /**
     * @return int|string
     */
    public static function getUserId()
    {
        return Yii::$app->user->id ?? null;
    }

    /**
     * @return int|string
     */
    public static function getUserLevelId()
    {
        return Yii::$app->user->identity->level_id ?? null;
    }

    /**
     * @return int|string
     */
    public static function getUserRoleId()
    {
        return Yii::$app->user->identity->role_id ?? null;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return Yii::$app->user->identity->name ?? null;
    }
}