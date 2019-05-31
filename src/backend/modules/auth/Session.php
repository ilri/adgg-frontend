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
        return Yii::$app->user->identity->level_id == UserLevels::LEVEL_COUNTRY;
    }

    /**
     * @return bool
     */
    public static function isPrivilegedAdmin()
    {
        return static::isDev() || static::isSuperAdmin() || static::isSystemAdmin();
    }

    /**
     * @return mixed
     */
    public static function accountId()
    {
        return Yii::$app->user->identity->org_id ?? null;
    }


    /**
     * @return int|string
     */
    public static function userId()
    {
        return Yii::$app->user->id ?? null;
    }

    /**
     * @return int|string
     */
    public static function userLevelId()
    {
        return Yii::$app->user->identity->level_id ?? null;
    }

    /**
     * @return int|string
     */
    public static function userRoleId()
    {
        return Yii::$app->user->identity->role_id ?? null;
    }

    /**
     * @return string
     */
    public static function userName()
    {
        return Yii::$app->user->identity->name ?? null;
    }
}