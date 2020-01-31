<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\AIEvent;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\ExitsEvent;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FeedingEvent;
use backend\modules\core\models\HealthEvent;
use backend\modules\core\models\MilkingEvent;
use backend\modules\core\models\PDEvent;
use backend\modules\core\models\SyncEvent;
use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\WeightEvent;
use common\helpers\DbUtils;
use common\helpers\Str;
use common\helpers\Utils;
use common\models\ActiveRecord;
use yii\base\Model;
use yii\db\Expression;
use yii\helpers\Inflector;

class ReportBuilder extends Model
{
    /**
     * @var string
     */
    public $model;
    /**
     * @var array
     */
    public $filterConditions;
    /**
     * @var array
     */
    public $filterValues;
    /**
     * @var array
     */
    public $fields;
    /**
     * @var int
     */
    public $limit;
    /**
     * @var string
     */
    public $orderBy;
    /**
     * @var int
     */
    public $org_id;
    /**
     * @var string
     */
    public $name;

    /**
     * @return array
     */
    public static function reportableModels(){
        return [
            'Farm' => [
                'class' => Farm::class,
                'title' => 'Farm',
                'relations' => ['fieldAgent', 'region', 'district', 'ward', 'village'],
            ],
            'Animal' => [
                'class' => Animal::class,
                'title' => 'Animal',
                'relations' => ['farm', 'herd', 'sire', 'dam', 'region', 'district', 'ward', 'village'],
            ],
            'Calving_Event' => [
                'class' => CalvingEvent::class,
                'title' => 'Calving Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_CALVING],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Milking_Event' => [
                'class' => MilkingEvent::class,
                'title' => 'Milking Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_MILKING],
                'relations' => ['lactation','animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Insemination_Event' => [
                'class' => AIEvent::class,
                'title' => 'Insemination Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_AI],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Pregnancy_Diagnosis_Event' => [
                'class' => PDEvent::class,
                'title' => 'Pregnancy Diagnosis Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Synchronization_Event' => [
                'class' => SyncEvent::class,
                'title' => 'Synchronization Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_SYNCHRONIZATION],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Weights_Event' => [
                'class' => WeightEvent::class,
                'title' => 'Weights Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Health_Event' => [
                'class' => HealthEvent::class,
                'title' => 'Health Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_HEALTH],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Feeding_Event' => [
                'class' => FeedingEvent::class,
                'title' => 'Feeding Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_FEEDING],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
                'sub_relations' => ['animal.farm' => ['animal.farm_id' => 'farm.id']],
            ],
            'Exits_Event' => [
                'class' => ExitsEvent::class,
                'title' => 'Exits Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_EXITS],
                'relations' => ['animal', 'region', 'district', 'ward', 'village'],
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
     * @param string|null $field_alias
     * @param bool append_field_alias
     * @return string
     */
    public static function getFullColumnName($field, $class, $field_alias = null, $append_field_alias = false){
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
                $tableAlias = $subRelationName;
                $fieldLabelAlias = ucfirst($subRelationName);
            }
            else{
                // farm.farmer_name
                $relationName = (explode('.', $field)[0]); // farm
                $fieldName = (explode('.', $field)[1]); // farmer_name
                $modelClass = static::getRelationClass($class, $relationName);

                // append table name || relationName to field to remove ambiguity.
                $tableAlias = $relationName;
                $fieldLabelAlias = ucfirst($relationName);
            }
        }
        else {
            $modelClass = $class;
            // append table name to field to remove ambiguity.
            $fieldName = $field;
            $tableAlias = $modelClass::tableName();
            $fieldLabelAlias = $modelClass::shortClassName();
        }
        # quote the table alias
        $tableAlias = \Yii::$app->db->quoteTableName($tableAlias);

        # append alias to field to remove ambiguity
        $aliasedField = $tableAlias.'. [['.$fieldName.']]';

        # additional columns
        if($modelClass->hasMethod('isAdditionalAttribute')){
            if ($modelClass->isAdditionalAttribute($fieldName)){
                # for additional attributes, find a way to get their values
                $attributeModel = TableAttribute::find()->andWhere(['attribute_key' => $fieldName, 'table_id' => $modelClass::getDefinedTableId()])->one();
                $id = $attributeModel->id;
                $attributesColumn = "{$tableAlias}.[[additional_attributes]]";
                # get the value of this field from the json payload
                # e.g JSON_EXTRACT(`core_farm`.`additional_attributes`, '$."34"')
                $aliasedField = new Expression('JSON_UNQUOTE(JSON_EXTRACT('.$attributesColumn.', '."'".'$."'.$id.'"'."'".'))');

            }
        }
        //$className = $modelClass::shortClassName();
        $attrLabel = $modelClass->getAttributeLabel($fieldName);
        # remove special characters from $attrLabel except underscore
        $attrLabel = preg_replace('/[^a-zA-Z0-9_]/', '', $attrLabel);

        # convert label to camelCase if there are spaces in the word
        $attrLabel = Inflector::camelize($attrLabel);
        if(strpos($modelClass->getAttributeLabel($fieldName), ' ' )){
            $attrLabel = Inflector::variablize($attrLabel);
        }

        $fieldAlias = $fieldLabelAlias . '_' .$attrLabel;

        if($append_field_alias){
            if($field_alias === null){
                return $aliasedField . ' AS [[' . $fieldAlias .']]';
            }
            else {
                return $aliasedField . ' AS [[' . $field_alias .']]';
            }
        }
        else{
            return $aliasedField;
        }
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
            $fieldAlias = str_replace('.','_', $field);

            if(strpos($field, '.')){
                if(substr_count($field, '.') > 1){
                    // animal.farm.farmer_name
                    $relationName = (explode('.', $field)[0]); // animal
                    $subRelationName = (explode('.', $field)[1]); // farm
                    // add subrelation to other joins
                    $other_joins[$relationName] = $subRelationName;
                }
                else {
                    $relationName = (explode('.', $field)[0]);
                    $joins[] = $relationName;
                }
            }
            # field with table alias
            $aliasedField = static::getFullColumnName($field, $class);
            # field with table and column alias
            $selectField = static::getFullColumnName($field, $class, null, true);

            // add field to select
            //$query->addSelect(new Expression( $aliasedField . ' AS "' . $field . '"'));
            $query->addSelect(new Expression($selectField));

            // build the condition
            if (!empty($conditionOperator)){
                // get the condition value for this field
                $filter = $this->filterValues[$field];
                $sqlCondition = static::buildCondition($conditionOperator, $aliasedField, $filter);
                $query->andFilterWhere($sqlCondition);
            }
        }
        // do the select
        //$query->addSelect($attributes);
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