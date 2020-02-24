<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/06
 * Time: 4:44 PM
 */

namespace common\helpers;

use Yii;
use yii\base\NotSupportedException;
use yii\db\Connection;

class DbUtils
{
    //DB DRIVERS
    const DRIVER_MYSQL = 'mysql';
    const DRIVER_POSTGRES = 'pgsql';

    /**
     * @param string $column the table column name
     * @param string $value the column value
     * @param string|array $condition the current condition
     * @param array $params the current params
     * @param string $conditionConnector
     * @param string $operator
     * @return array
     * @throws \Exception
     */
    public static function appendCondition($column, $value, $condition = '', $params = [], $conditionConnector = 'AND', $operator = '=')
    {
        $paramKey = Str::removeNonAlphaNumericCharacters($column);
        if (!is_array($condition)) {
            if (strtolower($operator) === 'like') {
                $value = '%' . $value . '%';
            }
            if (!empty($condition))
                $condition .= ' ' . $conditionConnector . ' ';
            $i = rand(1, 100);
            $escapedColumn = static::escapeColumn($column);
            if ($value === null && ($operator === '=' || $operator === '<>' || $operator === '!=')) {
                if ($operator === '=') {
                    $condition .= $escapedColumn . ' IS NULL';
                } else {
                    $condition .= $escapedColumn . ' IS NOT NULL';
                }
            } else {
                $condition .= $escapedColumn . ' ' . $operator . ' :mc' . $paramKey . $i;
                $params[':mc' . $paramKey . $i] = $value;
            }

        } else {
            $condition[$column] = $value;
        }

        return [$condition, $params];
    }

    /**
     * @param $dateField
     * @param Connection $db
     * @return string
     */
    public static function castDATE($dateField, $db = null)
    {
        $escapedDateField = static::escapeColumn($dateField);
        $default = 'DATE(' . $escapedDateField . ')';
        if (is_null($db))
            $db = Yii::$app->db;

        switch ($db->getDriverName()) {
            case self::DRIVER_POSTGRES:
            case self::DRIVER_MYSQL:
                return $default;
                break;
            default:
                return $default;

        }
    }

    public static function castMONTH($dateField, $db = null)
    {
        $escapedDateField = static::escapeColumn($dateField);
        $default = 'MONTH(' . $escapedDateField . ')';
        if (is_null($db))
            $db = Yii::$app->db;

        switch ($db->getDriverName()) {
            case self::DRIVER_MYSQL:
                return $default;
                break;
            case self::DRIVER_POSTGRES:
                return 'extract(month from ' . $escapedDateField . ')';
                break;
            default:
                return $default;

        }
    }

    /**
     * @param $dateField
     * @param Connection $db
     * @return string
     */
    public static function castYEAR($dateField, $db = null)
    {
        $escapedDateField = static::escapeColumn($dateField);
        $default = 'YEAR(' . $escapedDateField . ')';
        if (is_null($db))
            $db = Yii::$app->db;

        switch ($db->getDriverName()) {
            case self::DRIVER_MYSQL:
                return $default;
                break;
            case self::DRIVER_POSTGRES:
                return 'extract(year from ' . $escapedDateField . ')';
                break;
            default:
                return $default;

        }
    }

    /**
     * @param $dateField
     * @param $dateValue
     * @param Connection $db
     * @param string $condition
     * @param array $params
     * @return array
     * @throws NotSupportedException
     */
    public static function YEARWEEKCondition($dateField, $dateValue, $db = null, $condition = '', $params = [])
    {
        if (is_null($db))
            $db = Yii::$app->db;
        if (!empty($condition))
            $condition .= ' AND ';
        $escapedDateField = static::escapeColumn($dateField);

        switch ($db->getDriverName()) {
            case self::DRIVER_MYSQL:
                $condition .= 'YEARWEEK(' . $escapedDateField . ',1)=YEARWEEK(:' . $dateField . ',1)';
                $params[':' . $dateField] = $dateValue;
                break;
            case self::DRIVER_POSTGRES:
                $condition .= 'to_char(' . $escapedDateField . ',\'IYYY_IW\')=to_char(:' . $dateField . '::date,\'IYYY_IW\')';
                $params[':' . $dateField] = $dateValue;
                break;
            default:
                throw new NotSupportedException('MSSQL is not supported yet.');
        }

        return [$condition, $params];
    }

    /**
     * get IN condition and params
     * @param $column
     * @param $values
     * @param mixed $condition
     * @param array $params
     * @param string $operator IN | NOT IN
     * @return array
     * @throws \Exception
     */
    public static function appendInCondition($column, $values, $condition = '', $params = [], $operator = 'IN')
    {
        if (is_array($condition)) {
            $condition[$column] = $values;
            return [$condition, $params];
        }

        if (!empty($condition))
            $condition .= ' AND ';
        $param_count = 0;
        $escapedColumn = static::escapeColumn($column);
        $paramKey = Str::removeNonAlphaNumericCharacters($column);
        $param_prefix = ':mc_' . $paramKey;
        if (($n = count($values)) < 1)
            $condition .= '0=1';
        // 0=1 is used because in MSSQL value alone can't be used in WHERE
        elseif ($n === 1) {
            $value = reset($values);
            if ($value === null)
                $condition .= $escapedColumn . ' IS NULL';
            else {
                $operator = strtolower($operator) === 'in' ? '=' : '<>';
                $condition .= $escapedColumn . $operator . $param_prefix . $param_count;
                $params[$param_prefix . $param_count] = $value;
            }
        } else {
            $in = [];
            foreach ($values as $value) {
                $in[] = $param_prefix . $param_count;
                $params[$param_prefix . $param_count++] = $value;
            }
            $condition .= $escapedColumn . ' ' . $operator . ' (' . implode(', ', $in) . ')';
        }

        return [$condition, $params];
    }

    public static function escapeColumn($column)
    {
        if (!strpos($column, '(') && !strpos($column, '.') && !strpos($column, '[')) {
            $column = '[[' . $column . ']]';
        }
        return $column;
    }
}
