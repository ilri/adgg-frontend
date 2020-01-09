<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-20
 * Time: 9:40 AM
 */

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use common\helpers\DbUtils;
use common\helpers\Str;
use common\helpers\Utils;
use common\models\ActiveRecord;
use yii\base\Model;
use yii\db\Expression;

class ReportBuilder extends Model
{
    public $model;
    public $filterConditions;
    public $filterValues;
    public $fields;
    public $limit;
    public $orderBy;

    /**
     * @return array
     */
    public static function reportableModels(){
        return [
            'Farm' => [
                'class' => Farm::class,
                'relations' => ['fieldAgent'],
            ],
            'Animal' => [
                'class' => Animal::class,
                'relations' => ['farm', 'herd', 'sire', 'dam'],
            ],
        ];
    }

    public static function fieldConditionOptions($prompt = false)
    {
        $values = [
            '=' => 'Equal To',
            '>' => 'Greater Than',
            '<' => 'Less Than',
            '>=' => 'Greater or Equal To',
            '<=' => 'Less Than or Equal To',
            'LIKE' => 'LIKE',
            'IS NULL' => 'NULL',
            'NOT NULL' => 'NOT NULL',
        ];
        return Utils::appendDropDownListPrompt($values, $prompt);
    }

    /**
     * @return array
     */
    public static function buildAttributeList(){
        return [];
    }

    /**
     * @param string $operator
     * @param string $column
     * @param mixed $value
     * @return mixed
     */
    public static function buildCondition($operator, $column, $value)
    {
        $null = new Expression('NULL');
        switch ($operator){
            case 'IS NULL':
                return ['IS', $column, $null];
            case 'NOT NULL':
                return ['IS NOT', $column, $null];
            default:
                return [$operator, $column, $value];
        }

    }

    /**
     * @param \yii\db\ActiveRecord $class
     * @param string $relationName
     * @return \yii\db\ActiveRecord
     */
    public static function getRelationClass($class, $relationName){
        /* @var $class ActiveRecord */
        $relation = $class->getRelation($relationName);
        /* @var $relationModelClass ActiveRecord */
        $relationModelClass = new $relation->modelClass();
        return $relationModelClass;
    }

    /**
     * @param string $modelName
     * @return \yii\db\ActiveRecord
     */
    public static function getModelClass($modelName){
        $className = static::reportableModels()[$modelName]['class'];
        return new $className();
    }

    /**
     * @param string $field
     * @param \yii\db\ActiveRecord $class
     * @return string
     */
    public static function getFullColumnName($field, $class){
        // check if field is a joined relation
        if(strpos($field, '.')){
            $relationName = (explode('.', $field)[0]);
            $fieldName = (explode('.', $field)[1]);
            $relationModelClass = static::getRelationClass($class, $relationName);

            $tableName = $relationModelClass::tableName();
            // append table name || relationName to field to remove ambiguity.
            $aliasedField = $relationName.'.'.$fieldName;
        }
        else {
            // append table name to field to remove ambiguity.
            $aliasedField = $class::tableName().'.'.$field;
        }

        return $aliasedField;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function generateQuery(){
        $className = static::reportableModels()[$this->model]['class'];
        /* @var $class ActiveRecord */
        $class = new $className();
        $attributes = [];
        $joins = [];

        // start the query
        $query = $class::find();
        // get the attributes for select
        foreach ($this->filterConditions as $field => $conditionOperator){
            // check if field is a joined relation
            if(strpos($field, '.')){
                $relationName = (explode('.', $field)[0]);
                $fieldName = (explode('.', $field)[1]);
                $relation = $class->getRelation($relationName);
                /* @var $relationModelClass ActiveRecord */
                $relationModelClass = new $relation->modelClass();

                $tableName = $relationModelClass::tableName();
                $joins[] = $relationName;
                // append table name || relationName to field to remove ambiguity.
                $aliasedField = $relationName.'.'.$fieldName;
            }
            else {
                // append table name to field to remove ambiguity.
                $aliasedField = $class::tableName().'.'.$field;
            }
            $attributes[] = $aliasedField;

            // build the condition
            if (!empty($conditionOperator)){
                // get the condition value for this field
                $filter = $this->filterValues[$field];
                $sqlCondition = static::buildCondition($conditionOperator, $aliasedField, $filter);
                $query->andFilterWhere($sqlCondition);
            }
        }
        // do the select
        $query->select($attributes);
        // do the joins
        if (count($joins)){
            foreach (array_unique($joins) as $join){
                //$query->joinWith($join . ' as ' . $join);
                $query->joinWith([
                    $join => function(\yii\db\ActiveQuery $q) use ($join, $class){
                        $object = static::getRelationClass($class, $join);
                        $q->from([$join => $object::tableName()]);
                        $q->alias($join);
                        $q->where([]);
                        if ($object->hasAttribute('is_deleted')) {
                            $q->andWhere(['[['.$join.']]' . '.[[is_deleted]]' => 0]);
                        }
                    }
                ]);
            }
        }

        // do limit and orderBy
        if($this->limit){
            $query->limit($this->limit);
        }
        if ($this->orderBy){
            // should be a fully qualified column name
            $query->orderBy(static::getFullColumnName($this->orderBy, $class));
        }
        return $query;

    }

    /**
     * @return string
     */
    public function rawQuery(){
        return $this->generateQuery()->createCommand()->rawSql;
    }

    public function saveReport(){

    }
}