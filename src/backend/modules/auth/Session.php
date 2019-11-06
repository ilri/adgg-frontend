<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/06/21
 * Time: 7:47 PM
 */

namespace backend\modules\auth;


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
    public static function isOrganization()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        $orgUserLevels = [
            UserLevels::LEVEL_COUNTRY,
            UserLevels::LEVEL_REGION,
            UserLevels::LEVEL_DISTRICT,
            UserLevels::LEVEL_WARD,
            UserLevels::LEVEL_VILLAGE,
        ];
        return in_array(Yii::$app->user->identity->level_id, $orgUserLevels);
    }

    public static function isCountryUser()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_COUNTRY;
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
     * @param null|string|int $default
     * @return mixed
     */
    public static function getOrgId($default = null)
    {
        if (static::isOrganization()) {
            return Yii::$app->user->identity->org_id ?? null;
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