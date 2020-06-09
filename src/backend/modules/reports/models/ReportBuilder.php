<?php

namespace backend\modules\reports\models;

use backend\modules\core\models\AIEvent;
use backend\modules\core\models\Animal;
use backend\modules\core\models\AnimalEvent;
use backend\modules\core\models\CalvingEvent;
use backend\modules\core\models\ExitsEvent;
use backend\modules\core\models\Farm;
use backend\modules\core\models\FarmMetadataBreedingAIProviders;
use backend\modules\core\models\FarmMetadataBreedingBulls;
use backend\modules\core\models\FarmMetadataBreedingOtherBulls;
use backend\modules\core\models\FarmMetadataBreedingSchemeBulls;
use backend\modules\core\models\FarmMetadataFeeding;
use backend\modules\core\models\FarmMetadataHealth;
use backend\modules\core\models\HealthEvent;
use backend\modules\core\models\MilkingEvent;
use backend\modules\core\models\PDEvent;
use backend\modules\core\models\SyncEvent;
use backend\modules\core\models\TableAttribute;
use backend\modules\core\models\WeightEvent;
use common\helpers\ArrayHelper;
use common\helpers\Utils;
use common\models\ActiveRecord;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Schema;
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
     * Fields that are needed to generate the correct query, e.g filtering but do not need to be in the final user-viewable report
     * keys are the field names
     * @var array
     */
    public $excludeFromReport = [];
    /**
     * Extra SQL expressions that need to be appended to the report, e.g an inner select or computed queries
     * @var array
     */
    public $extraSelectExpressions = [];
    /**
     * @var array
     */
    /**
     * Extra SQL expressions that need to be appended to the where clauses, e.g a date range filter
     * @var array
     */
    public $extraFilterExpressions = [];
    /**
     * Fields that need decoding and how to decode them
     * keys are the field names
     * @var array
     */
    public $decodeFields = [];
    /**
     * full name of function to modify each row when populating csv
     * e.g, add additional columns, decode data etc.
     * this will replace the need to populate $decodeFields[]
     * @var string
     */
    public $rowTransformer = null;
    /**
     * @var array
     */
    public $fields;
    /**
     * @var array
     */
    public $fieldAliases;
    /**
     * @var array
     */
    public $fieldAliasMapping = [];
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
    public $country_id;
    /**
     * @var string
     */
    public $name;

    /**
     * @return array
     */
    public static function reportableModels()
    {
        $eventRelations = ['animal', 'country', 'region', 'district', 'ward', 'village', 'org', 'client'];
        return [
            'Farm' => [
                'class' => Farm::class,
                'title' => 'Farm',
                'relations' => ['fieldAgent', 'country', 'region', 'district', 'ward', 'village', 'org', 'client'],
            ],
            'FarmMetadataBreedingAIProviders' =>[
                'class' => FarmMetadataBreedingAIProviders::class,
                'title' =>'Farm Breeding AIProviders Metadata',
                'relations' => ['farm'],
            ],
            'FarmMetadataBreedingBulls' =>[
                'class' => FarmMetadataBreedingBulls::class,
                'title' =>'Farm Breeding Bulls Metadata',
                'relations' => ['farm'],
            ],
            'FarmMetadataBreedingOtherBulls' =>[
                'class' => FarmMetadataBreedingOtherBulls::class,
                'title' =>'Farm Breeding Other Bulls Metadata',
                'relations' => ['farm'],
            ],
            'FarmMetadataBreedingSchemeBulls' =>[
                'class' => FarmMetadataBreedingSchemeBulls::class,
                'title' =>'Farm Breeding Scheme Bulls Metadata',
                'relations' => ['farm'],
            ],
            'FarmMetadataFeeding' =>[
                'class' => FarmMetadataFeeding::class,
                'title' =>'Farm Feeding Metadata',
                'relations' => ['farm'],
            ],
            'FarmMetadataHealth' =>[
                'class' => FarmMetadataHealth::class,
                'title' =>'Farm Health Metadata',
                'relations' => ['farm'],
            ],
            'Animal' => [
                'class' => Animal::class,
                'title' => 'Animal',
                'relations' => ['farm', 'herd', 'sire', 'dam', 'country', 'region', 'district', 'ward', 'village', 'org', 'client'],
            ],
            'Calving_Event' => [
                'class' => CalvingEvent::class,
                'title' => 'Calving Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_CALVING],
                'relations' => $eventRelations,
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
            ],
            'Milking_Event' => [
                'class' => MilkingEvent::class,
                'title' => 'Milking Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_MILKING],
                'relations' => array_merge(['lactation'], $eventRelations),
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
            ],
            'Insemination_Event' => [
                'class' => AIEvent::class,
                'title' => 'Insemination Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_AI],
                'relations' => $eventRelations,
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
            ],
            'Pregnancy_Diagnosis_Event' => [
                'class' => PDEvent::class,
                'title' => 'Pregnancy Diagnosis Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS],
                'relations' => $eventRelations,
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
            ],
            'Synchronization_Event' => [
                'class' => SyncEvent::class,
                'title' => 'Synchronization Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_SYNCHRONIZATION],
                'relations' => $eventRelations,
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
            ],
            'Weights_Event' => [
                'class' => WeightEvent::class,
                'title' => 'Weights Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS],
                'relations' => $eventRelations,
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
            ],
            'Health_Event' => [
                'class' => HealthEvent::class,
                'title' => 'Health Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_HEALTH],
                'relations' => $eventRelations,
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
            ],
            'Exits_Event' => [
                'class' => ExitsEvent::class,
                'title' => 'Exits Events',
                'extraCondition' => ['event_type' => AnimalEvent::EVENT_TYPE_EXITS],
                'relations' => $eventRelations,
                'sub_relations' => [
                    'animal.farm' => ['animal.farm_id' => 'farm.id'],
                    'animal.herd' => ['animal.herd_id' => 'herd.id'],
                    'animal.sire' => ['animal.sire_id' => 'sire.id'],
                    'animal.dam' => ['animal.dam_id' => 'dam.id'],
                ],
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
            'BETWEEN' => 'BETWEEN',
            'IN' => 'IN',
            'NOT IN' => 'NOT IN',
        ];
        return Utils::appendDropDownListPrompt($values, $prompt);
    }

    /**
     * @param string $reportModel
     * @return array
     */
    public static function buildAttributeList(string $reportModel)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = static::getReportModelClass($reportModel);
        return static::buildModelTree($modelClass);
    }

    /**
     * @param ActiveRecord $model
     * @param int $currentLevel
     * @param int $maxLevel
     * @return array
     */
    public static function buildModelTree(ActiveRecord $model, $currentLevel = 0, $maxLevel = 2, $parent = null, $isRelation = false, $relationName = null)
    {
        // increment $currentLevel each time this function is called
        $currentLevel++;
        /* @var $model ActiveRecord */
        $attributes = $model->reportBuilderFields();
        $relations = $model->reportBuilderRelations();
        $name = $model::shortClassName();
        $tree = [];
        $tree['parent'] = $parent;
        $tree['level'] = $currentLevel;
        $tree['is_relation'] = $isRelation;
        $tree['relation_name'] = $relationName;
        /*
        foreach ($attributes as $attribute){
            //$level = $currentLevel;
            $parentToArray = explode('.', $parent);
            //$parentToArray = [$parent, $relationName];
            $tree['parents'] = $parentToArray;
            if($currentLevel == 2){
                $tree['selected_parents'] = array_slice($parentToArray, -1, 1);
            }
            elseif($currentLevel > 2) {
                // 3-1 = 2, get the second item in parents
                $toget = $currentLevel - 1;
                $filtered = array_merge(array_slice($parentToArray, -$toget, $toget), array_slice($parentToArray, -1, 1));
                $tree['selected_parents'] = array_unique($filtered);
            }else {

            }

            $tree['attributes'][] = $parent !== null ? $parent . '.' . $attribute : $attribute;
        }
        */
        $tree['attributes'] = $attributes;
        //$parent = null;
        // check if we have reached the maxLevel for nesting the relations, to avoid an infinite loop or deep
        if ($currentLevel <= $maxLevel) {
            // build attribute tree for each relation, recursively...
            foreach ($relations as $relation) {
                $relationClass = static::getRelationClass($model, $relation);
                $parent = $parent !== null ? $parent . '.' .$relation : $relation;
                //$parent = $relationName;
                //$parent = $relation;
                $tree['level'] = $currentLevel;
                $tree['relations'][$relation] = static::buildModelTree($relationClass, $currentLevel, $maxLevel, $parent,true, $relation);
            }
        }
        return $tree;
    }

    public static function buildAttributesHTML(array $attributes){
        $html = '';
    }

    public static function renderReportModelHTML(string $reportModel){
        $modelOptions = static::reportableModels()[$reportModel];
        /* @var $class ActiveRecord */
        $class = new $modelOptions['class']();
        //$title = $modelOptions['title'] ?? $reportModel;
        $modelOptions['name'] = $reportModel;
        $modelOptions['currentLevel'] = 0;
        $modelOptions['maxLevel'] = 1;
        $html = '';
        $html .= self::renderModelHTML($class, $modelOptions);
        return $html;
    }

    public static function renderModelHTML(ActiveRecord $model, array $reportModelOptions, $parents = [], $is_relation = false){
        $name = $reportModelOptions['name'];
        $title = $reportModelOptions['title'] ?? $reportModelOptions['name'];
        $tree = static::buildModelTree($model, $reportModelOptions['currentLevel'], $reportModelOptions['maxLevel']);
        $html = '';
        //$html .= static::buildAttributesHTML($tree['attributes']);
        foreach ($tree['attributes'] as $attribute){
            // check how many parents this model has so that we can appropriately name the attributes
            $html .= \Yii::$app->controller->renderPartial('partials/_attribute', [
                'attribute' => $attribute,
                'attributeTitle' => count($parents) ? (implode('.', $parents) . ' . '. $model->getAttributeLabel($attribute)) : $model->getAttributeLabel($attribute),
                'attributeName' => count($parents) ? (implode('.', $parents) . ' . '. $attribute) : $attribute,
                'attributeLabel' => $model->getAttributeLabel($attribute),
                'class' => $model,
                'modelName' => $name,
                'parentModelName' => $name,
                'parentModelTitle' => $title,
            ]);
        }
        if(array_key_exists('relations', $tree)){
            foreach ($tree['relations'] as $relation => $attrs){
                $relationName = $relation;
                $html .= '<li data-toggle="collapse"';
                $html .= '   data-target="#collapse'.$relationName .'"';
                $html .= '  aria-expanded="false"';
                $html .= '  aria-controls="collapse'.$relationName .'">';
                $html .= '  > '.$relationName .'</li>';

                $relationClass = static::getRelationClass($model, $relation);
                // set all the parents of this relation and use it when determining the attribute names to display
                $rparents[] = $relationName;
                $html .= static::renderModelHTML($relationClass, $reportModelOptions, $rparents, true);
            }
        }
        return $html;

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
        switch ($operator) {
            //case '>':
            //case '<':
            //case '<=':
            //case '>=':
            //    return [$operator, $column, intval($value)];
            case 'IS NULL':
                return ['IS', $column, $null];
            case 'NOT NULL':
                return ['IS NOT', $column, $null];
            case 'BETWEEN':
                return ['BETWEEN', $column, $value[0] ?? '', $value[1] ?? ''];
            case 'IN':
            case 'NOT IN':
                $values = [];
                if(is_array($value)){
                    $values = $value;
                }
                if(is_string($value)) {
                    $values = array_map('trim', explode(',', $value));
                }
                if(empty($values)){
                    return [];
                }
                return [$operator, $column, $values];
            default:
                return [$operator, $column, $value];
        }

    }

    /**
     * @param \yii\db\ActiveRecord $class
     * @param string $relationName
     * @return \yii\db\ActiveRecord
     */
    public static function getRelationClass($class, $relationName)
    {
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
    public static function getReportModelClass($modelName)
    {
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
    public static function getFullColumnName($field, $class, $field_alias = null, $append_field_alias = false)
    {
        // check if field is a joined relation
        if (strpos($field, '.')) {
            if (substr_count($field, '.') > 1) {
                // animal.farm.farmer_name
                $relationName = (explode('.', $field)[0]); // animal
                $subRelationName = (explode('.', $field)[1]); // farm
                $fieldName = (explode('.', $field)[2]); // farmer_name

                $relationClass = static::getRelationClass($class, $relationName); // Animal::class
                $subRelationClass = static::getRelationClass($relationClass, $subRelationName); // Farm::class
                $modelClass = $subRelationClass;
                $tableAlias = $subRelationName;
                $fieldLabelAlias = ucfirst($subRelationName);
            } else {
                // farm.farmer_name
                $relationName = (explode('.', $field)[0]); // farm
                $fieldName = (explode('.', $field)[1]); // farmer_name
                $modelClass = static::getRelationClass($class, $relationName);

                // append table name || relationName to field to remove ambiguity.
                $tableAlias = $relationName;
                $fieldLabelAlias = ucfirst($relationName);
            }
        } else {
            $modelClass = $class;
            // append table name to field to remove ambiguity.
            $fieldName = $field;
            $tableAlias = $modelClass::tableName();
            $fieldLabelAlias = $modelClass::shortClassName();
        }
        # quote the table alias
        $tableAlias = \Yii::$app->db->quoteTableName($tableAlias);

        # append alias to field to remove ambiguity
        $aliasedField = $tableAlias . '.[[' . $fieldName . ']]';

        # additional columns
        if ($modelClass->hasMethod('isAdditionalAttribute')) {
            if ($modelClass->isAdditionalAttribute($fieldName)) {
                # for additional attributes, find a way to get their values
                $attributeModel = TableAttribute::find()->andWhere(['attribute_key' => $fieldName, 'table_id' => $modelClass::getDefinedTableId()])->one();
                $id = $attributeModel->id;
                $attributesColumn = "{$tableAlias}.[[additional_attributes]]";
                # get the value of this field from the json payload
                # e.g JSON_EXTRACT(`core_farm`.`additional_attributes`, '$."34"')
                $aliasedField = new Expression('JSON_UNQUOTE(JSON_EXTRACT(' . $attributesColumn . ', ' . "'" . '$."' . $id . '"' . "'" . '))');

            }
        }
        //$className = $modelClass::shortClassName();
        $attrLabel = $modelClass->getAttributeLabel($fieldName);
        # remove special characters from $attrLabel except underscore
        $attrLabel = preg_replace('/[^a-zA-Z0-9_]/', '', $attrLabel);

        # convert label to camelCase if there are spaces in the word
        $attrLabel = Inflector::camelize($attrLabel);
        if (strpos($modelClass->getAttributeLabel($fieldName), ' ')) {
            $attrLabel = Inflector::variablize($attrLabel);
        }

        $fieldAlias = $fieldLabelAlias . '_' . $attrLabel;

        if ($append_field_alias) {
            if ($field_alias === null) {
                return $aliasedField . ' AS [[' . $fieldAlias . ']]';
            } else {
                return $aliasedField . ' AS [[' . $field_alias . ']]';
            }
        } else {
            return $aliasedField;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function generateQuery()
    {
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
        foreach ($this->filterConditions as $field => $conditionOperator) {
            $fieldAlias = str_replace('.', '_', $field);
            $fieldModelClass = $class;
            $fieldName = $field;

            if (strpos($field, '.')) {
                if (substr_count($field, '.') > 1) {
                    // animal.farm.farmer_name
                    $relationName = (explode('.', $field)[0]); // animal
                    $subRelationName = (explode('.', $field)[1]); // farm
                    // add subrelation to other joins
                    $other_joins[$subRelationName] = $relationName;
                    $joins[] = $relationName;
                    $relationClass = static::getRelationClass($class, $relationName); // Animal::class
                    $fieldModelClass = static::getRelationClass($relationClass, $subRelationName); // Farm::class
                    $fieldName = (explode('.', $field)[2]);
                } else {
                    $relationName = (explode('.', $field)[0]);
                    $joins[] = $relationName;
                    $fieldModelClass = static::getRelationClass($class, $relationName);
                    $fieldName = (explode('.', $field)[1]);
                }
            }
            # field with table alias
            $aliasedField = static::getFullColumnName($field, $class);
            # field with table and column alias
            $field_alias = ArrayHelper::getValue($this->fieldAliases, $field);
            $selectField = static::getFullColumnName($field, $class, $field_alias, true);

            // add field to select
            //$query->addSelect(new Expression( $aliasedField . ' AS "' . $field . '"'));

            if (!in_array($field, $this->excludeFromReport)) {
                $query->addSelect(new Expression($selectField));
            }
            // extract alias from selectField

            $field_alias_array = explode('AS', $selectField);
            $generated_alias = str_replace('[[', '', $field_alias_array[1]);
            $generated_alias = str_replace(']]', '', $generated_alias);
            $generated_alias = trim($generated_alias);

            $this->fieldAliasMapping[$field] = $generated_alias;

            // build the condition
            if (!empty($conditionOperator)) {
                // get the condition value for this field
                $filter = $this->filterValues[$field] ?? '';
                #
                # handle other json columns that are not additional attributes in a special way
                #
                $columnDbType = $fieldModelClass->getAttributeSchemaType($fieldName);
                if ($columnDbType == Schema::TYPE_JSON){
                    //$params = [':field' => $aliasedField, ':value' => $filter];
                    //$sqlCondition = new Expression('JSON_SEARCH(:field,"one",:value)', $params);
                    if (is_array($filter) && !empty($filter)){
                        foreach ($filter as $value){
                            if ($conditionOperator == 'IN'){
                                $sqlCondition = new Expression('"'.$value.'" MEMBER OF('.$aliasedField.')');
                                $query->orWhere($sqlCondition);
                            }
                            elseif ($conditionOperator == 'NOT IN'){
                                $sqlCondition = new Expression('"'.$value.'" MEMBER OF('.$aliasedField.') = 0');
                                $query->andWhere($sqlCondition);
                            }
                        }
                    }
                    else if (!empty($filter)){
                        $sqlCondition = new Expression('"'.$filter.'" MEMBER OF('.$aliasedField.')');
                        $query->andWhere($sqlCondition);
                    }
                }
                else {
                    $sqlCondition = static::buildCondition($conditionOperator, $aliasedField, $filter);
                    $query->andFilterWhere($sqlCondition);
                }
            }
        }
        // do the select
        //$query->addSelect($attributes);
        // do the joins
        if (count($joins)) {
            foreach (array_unique($joins) as $join) {
                //$query->joinWith($join . ' as ' . $join);
                $query->joinWith([
                    $join => function (\yii\db\ActiveQuery $q) use ($join, $class) {
                        $object = static::getRelationClass($class, $join);
                        $q->from([$join => $object::tableName()]);
                        $q->alias($join);
                        $q->where([]);
                        if ($object->hasAttribute('is_deleted')) {
                            $q->andWhere(['[[' . $join . ']]' . '.[[is_deleted]]' => 0]);
                        }
                    }
                ]);
            }
        }
        //dd($joins, $other_joins);
        if (count($other_joins)) {
            foreach ($other_joins as $subRelationName => $relationName) {
                $link = $reportableModelOptions['sub_relations'][$relationName . '.' . $subRelationName];
                $modelClass = static::getRelationClass($class, $relationName); // Animal::class
                $subRelationClass = static::getRelationClass($modelClass, $subRelationName); // Farm::class
                $on = '';
                foreach ($link as $k => $f) {
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
        if ($this->limit) {
            $query->limit($this->limit);
        }
        if ($this->orderBy) {
            // should be a fully qualified column name
            $query->orderBy(static::getFullColumnName($this->orderBy, $class));
        }
        // if reportable model has extraCondition to be enforced, add it here
        if (array_key_exists('extraCondition', $reportableModelOptions)) {
            $condition = $reportableModelOptions['extraCondition'];
            if (count($condition)) {
                foreach ($condition as $f => $value) {
                    $aliasedField = static::getFullColumnName($f, $class);
                    $sqlCondition = static::buildCondition('=', $aliasedField, $value);
                    $query->andWhere($sqlCondition);
                }

            }
        }
        // if user selected a country, append the country_id
        // TODO: find a better way of doing all conditions in one place
        if ($this->country_id) {
            $aliasedField = static::getFullColumnName('country_id', $class);
            $sqlCondition = static::buildCondition('=', $aliasedField, $this->country_id);
            $query->andWhere($sqlCondition);
        }
        // append extra filters from elsewhere
        if (count($this->extraFilterExpressions)) {
            foreach ($this->extraFilterExpressions as $expression) {
                $query->andWhere($expression);
            }
        }
        // append extra select expressions, from elsewhere not in the UI
        if (count($this->extraSelectExpressions)) {
            foreach ($this->extraSelectExpressions as $expression) {
                $query->addSelect($expression);
            }
        }
        return $query;

    }

    /**
     * @return string
     */
    public function rawQuery()
    {
        return $this->generateQuery()->createCommand()->rawSql;
    }

    public function saveReport()
    {
        // save name, raw_query
        $report = new AdhocReport();
        $report->name = $this->name;
        $report->raw_sql = $this->rawQuery();
        $report->status = AdhocReport::STATUS_QUEUED;
        if ($report->save()) {
            return true;
        } else {
            return $report->getErrors();
        }
    }
}