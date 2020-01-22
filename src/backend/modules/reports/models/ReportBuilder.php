<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
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
    public $org_id;
    public $name;

    /**
     * @return array
     */
    public static function reportableModels(){
        return [
            'Farm' => [
                'class' => Farm::class,
                'title' => 'Farm',
                'relations' => ['fieldAgent', 'org', 'region', 'district', 'ward', 'village'],
            ],
            'Animal' => [
                'class' => Animal::class,
                'title' => 'Animal',
                'relations' => ['farm', 'herd', 'sire', 'dam', 'org', 'region', 'district', 'ward', 'village'],
            ],
            'Calving_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Calving Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_CALVING],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Milking_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Milking Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_MILKING],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Insemination_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Insemination Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_AI],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Pregnancy_Diagnosis_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Pregnancy Diagnosis Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Synchronization_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Synchronization Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_SYNCHRONIZATION],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Weights_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Weights Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Health_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Health Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_HEALTH],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Feeding_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Feeding Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_FEEDING],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Exits_Event' => [
                'class' => AnimalEvent::class,
                'title' => 'Exits Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_EXITS],
                'relations' => ['animal', 'org', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
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
            if(substr_count($field, '.') > 1){
                // animal.farm.farmer_name
                $relationName = (explode('.', $field)[0]); // animal
                $subRelationName = (explode('.', $field)[1]); // farm
                $fieldName = (explode('.', $field)[2]); // farmer_name

                $relationClass = static::getRelationClass($class, $relationName); // Animal::class
                $subRelationClass = static::getRelationClass($relationClass, $subRelationName); // Farm::class
                $modelClass = $subRelationClass;
                $fieldAlias = $subRelationName;
            }
            else{
                $relationName = (explode('.', $field)[0]);
                $fieldName = (explode('.', $field)[1]);
                $modelClass = static::getRelationClass($class, $relationName);

                // append table name || relationName to field to remove ambiguity.
                $fieldAlias = $relationName;
            }
        }
        else {
            $modelClass = $class;
            // append table name to field to remove ambiguity.
            $fieldName = $field;
            $fieldAlias = $modelClass::tableName();
        }

        # append alias to field to remove ambiguity
        $aliasedField = $fieldAlias.'.'.$fieldName;

        if($modelClass->hasMethod('isAdditionalAttribute')){
            if ($modelClass->isAdditionalAttribute($fieldName)){
                # for additional attributes, find a way to get their values
                $attributeModel = TableAttribute::find()->andWhere(['attribute_key' => $fieldName, 'table_id' => $modelClass::getDefinedTableId()])->one();
                $id = $attributeModel->id;
                $attributesColumn = $fieldAlias.'.[[additional_attributes]]';
                # get the value of this field from the json payload
                # e.g JSON_EXTRACT(`core_farm`.`additional_attributes`, '$."34"')
                $aliasedField = new Expression('JSON_UNQUOTE(JSON_EXTRACT('.$attributesColumn.', '."'".'$."'.$id.'"'."'".'))');

            }
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
        $reportableModelOptions = static::reportableModels()[$this->model];
        $main_attributes = [];
        $additional_attributes = [];
        $attributes = [];
        $joins = [];
        $other_joins = [];

        // start the query
        $query = $class::find();
        // get the attributes for select
        foreach ($this->filterConditions as $field => $conditionOperator){
            # check if field is a joined relation
            // TODO:: refactor all this and use getFullColumnName()
            if(strpos($field, '.')){
                if(substr_count($field, '.') > 1){
                    // animal.farm.farmer_name
                    $relationName = (explode('.', $field)[0]); // animal
                    $subRelationName = (explode('.', $field)[1]); // farm
                    $fieldName = (explode('.', $field)[2]); // farmer_name
                    $relationClass = static::getRelationClass($class, $relationName); // Animal::class
                    $subRelationClass = static::getRelationClass($relationClass, $subRelationName); // Farm::class
                    $modelClass = $subRelationClass;
                    $fieldAlias = $subRelationName;
                    $other_joins[$relationName] = $subRelationName;
                    // build the inner join manually

                    //  INNER JOIN `core_farm` `farm` ON `animal`.`farm_id` = `farm`.`id`
                    // get the subrelation join condition
                    // [animal.farm => ['animal.farm_id' => 'animal.id']]
                    //
                    /*
                    $link = $reportableModelOptions['sub_relations'][$relationName. '.' . $subRelationName];
                    $on = '';
                    foreach ($link as $k => $f){
                        // animal.farm_id
                        $on .= static::getFullColumnName($k, $class);
                        $on .= ' = ';
                        // farm.id
                        $on .= static::getFullColumnName($f, $modelClass);
                    }
                    $query->leftJoin($subRelationClass::tableName() . ' as ' . $subRelationName, $on);
                    */
                }
                else {
                    $relationName = (explode('.', $field)[0]);
                    $fieldName = (explode('.', $field)[1]);
                    $modelClass = static::getRelationClass($class, $relationName);
                    //$tableName = $modelClass::tableName();
                    $joins[] = $relationName;
                    # table name || relationName alias.
                    $fieldAlias = $relationName;
                }
            }
            else {
                $modelClass = $class;
                # table name || relationName alias.
                $fieldAlias = $class::tableName();
                $fieldName = $field;
            }

            # filter out additional attributes
            # if it's additional field we will handle it in special way

            if($modelClass->hasMethod('isAdditionalAttribute')){
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
                    $aliasedField = new Expression('JSON_UNQUOTE(JSON_EXTRACT('.$attributesColumn.', '."'".'$."'.$id.'"'."'".'))');
                    $query->addSelect($expression);
                }
            }
            else{
                // TODO: refactor repetition
                $main_attributes[] = $fieldName;
                # append alias to field to remove ambiguity
                $aliasedField = $fieldAlias.'.'.$fieldName;
                $attributes[] = $aliasedField;
            }

            // build the condition
            if (!empty($conditionOperator)){
                // get the condition value for this field
                $filter = $this->filterValues[$field];
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
        if (count($other_joins)){
            foreach (array_unique($other_joins) as $relationName => $subRelationName){
                $link = $reportableModelOptions['sub_relations'][$relationName. '.' . $subRelationName];
                $modelClass = static::getRelationClass($class, $relationName); // Animal::class
                $subRelationClass = static::getRelationClass($modelClass, $subRelationName); // Farm::class
                $on = '';
                foreach ($link as $k => $f){
                    // animal.farm_id
                    $on .= static::getFullColumnName($k, $class);
                    $on .= ' = ';
                    // farm.id
                    $on .= static::getFullColumnName($f, $modelClass);
                }
                $query->leftJoin($subRelationClass::tableName() . ' as ' . $subRelationName, $on);
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
        // if reportable model has extraCondition to be enforced, add it here
        if(array_key_exists('extraCondition', $reportableModelOptions)){
            $condition = $reportableModelOptions['extraCondition'];
            if (count($condition)){
                foreach ($condition as $f => $value){
                    $aliasedField = static::getFullColumnName($f,$class);
                    $sqlCondition = static::buildCondition('=', $aliasedField, $value);
                    $query->andWhere($sqlCondition);
                }

            }
        }
        // if user selected a country, append the org_id
        // TODO: find a better way of doing all conditions in one place
        if($this->org_id){
            $aliasedField = static::getFullColumnName('org_id', $class);
            $sqlCondition = static::buildCondition('=', $aliasedField, $this->org_id);
            $query->andWhere($sqlCondition);
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
        // save name, raw_query
        $report = new AdhocReport();
        $report->name = $this->name;
        $report->raw_sql = $this->rawQuery();
        $report->status = AdhocReport::STATUS_QUEUED;
        if($report->save()){
            return true;
        }
        else{
            return $report->getErrors();
        }
    }
}