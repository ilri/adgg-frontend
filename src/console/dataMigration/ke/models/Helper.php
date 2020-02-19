<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-19
 * Time: 11:06 PM
 */

namespace console\dataMigration\ke\models;


use backend\modules\core\models\Country;
use backend\modules\core\models\Organization;

class Helper
{
    /**
     * @param string $id
     * @param string $prefix
     * @return string
     */
    public static function getMigrationId($id, $prefix)
    {
        return $prefix . $id;
    }

    /**
     * @param int $countryCode
     * @return string|null
     * @throws \Exception
     */
    public static function getCountryId($countryCode)
    {
        $countryId = Country::getScalar('id', ['code' => $countryCode]);
        if (empty($countryId)) {
            return null;
        }
        return $countryId;
    }

    /**
     * @param $name
     * @return string|null
     * @throws \Exception
     */
    public static function getOrgId($name)
    {
        $orgId = Organization::getScalar('id', ['name' => $name]);
        if (empty($orgId)) {
            return null;
        }
        return $orgId;
    }
}