<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use backend\modules\core\models\TableAttribute;
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
            //case '>':
            //case '<':
            //case '<=':
            //case '>=':
            //    return [$operator, $column, intval($value)];
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
    public static function getReportModelClass($modelName){
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
            $modelClass = static::getRelationClass($class, $relationName);

            // append table name || relationName to field to remove ambiguity.
            $fieldAlias = $relationName;
        }
        else {
            $modelClass = $class;
            // append table name to field to remove ambiguity.
            $fieldName = $field;
            $fieldAlias = $modelClass::tableName();
        }

        if (!$modelClass->isAdditionalAttribute($fieldName)){
            $main_attributes[] = $fieldName;
            # append alias to field to remove ambiguity
            $aliasedField = $fieldAlias.'.'.$fieldName;
        }
        else {
            # for additional attributes, find a way to get their values
            $attributeModel = TableAttribute::find()->andWhere(['attribute_key' => $fieldName, 'table_id' => $modelClass::getDefinedTableId()])->one();
            $id = $attributeModel->id;
            $attributesColumn = $fieldAlias.'.[[additional_attributes]]';
            # get the value of this field from the json payload
            # e.g JSON_EXTRACT(`core_farm`.`additional_attributes`, '$."34"')
            $aliasedField = new Expression('JSON_UNQUOTE(JSON_EXTRACT('.$attributesColumn.', '."'".'$."'.$id.'"'."'".'))');
        }

        return $aliasedField;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function generateQuery(){
        //$className = static::reportableModels()[$this->model]['class'];
        /* @var $class ActiveRecord */
        //$class = new $className();
        $class = static::getReportModelClass($this->model);
        $main_attributes = [];
        $additional_attributes = [];
        $attributes = [];
        $joins = [];

        // start the query
        $query = $class::find();
        // get the attributes for select
        foreach ($this->filterConditions as $field => $conditionOperator){
            # check if field is a joined relation
            if(strpos($field, '.')){
                $relationName = (explode('.', $field)[0]);
                $fieldName = (explode('.', $field)[1]);
                $modelClass = static::getRelationClass($class, $relationName);
                //$tableName = $modelClass::tableName();
                $joins[] = $relationName;
                # table name || relationName alias.
                $fieldAlias = $relationName;
            }
            else {
                $modelClass = $class;
                # table name || relationName alias.
                $fieldAlias = $class::tableName();
                $fieldName = $field;
            }

            # filter out additional attributes
            # if it's additional field we will handle it in special way

            if (!$modelClass->isAdditionalAttribute($fieldName)){
                $main_attributes[] = $fieldName;
                # append alias to field to remove ambiguity
                $aliasedField = $fieldAlias.'.'.$fieldName;
                $attributes[] = $aliasedField;
            }
            else {
                # for additional attributes, find a way to get their values
                $additional_attributes[] = $fieldName;
                $attributeModel = TableAttribute::find()->andWhere(['attribute_key' => $fieldName, 'table_id' => $modelClass::getDefinedTableId()])->one();
                $id = $attributeModel->id;
                $attributesColumn = $fieldAlias.'.[[additional_attributes]]';
                # get the value of this field from the json payload
                # e.g JSON_EXTRACT(`core_farm`.`additional_attributes`, '$."34"') as `hh_name`
                $expression = new Expression('JSON_UNQUOTE(JSON_EXTRACT('.$attributesColumn.', '."'".'$."'.$id.'"'."'".')) as [[' . $fieldName .']]');
                // TODO: define a better way to search the json object
                $aliasedField = new Expression('JSON_UNQUOTE(JSON_EXTRACT('.$attributesColumn.', '."'".'$."'.$id.'"'."'".'))');
                $query->addSelect($expression);
            }

            // build the condition
            if (!empty($conditionOperator)){
                // get the condition value for this field
                $filter = $this->filterValues[$field];
                // TODO: define a better way to search the json object in additional_attributes
                $sqlCondition = static::buildCondition($conditionOperator, $aliasedField, $filter);
                $query->andFilterWhere($sqlCondition);
            }
        }
        // do the select
        $query->addSelect($attributes);
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