<?php

namespace backend\modules\core\models;

use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\db\Expression;

class MilkingReport extends Model
{
    public static function getLargeScaleFarmMilkDetails($country_id = null)
    {
        $query = AnimalEvent::find();
        //$query->select('core_animal_event.id, core_animal_event.animal_id');
        $query->addSelect('farm.farmer_name');
        $query->innerJoin(Animal::tableName() . ' animal', 'animal.id = core_animal_event.animal_id');
        $query->innerJoin(Farm::tableName() . ' farm', 'farm.id = animal.farm_id');
        $query->andWhere(['core_animal_event.event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $query->andWhere(['core_animal_event.country_id' => $country_id]);
        $query->andWhere(['farm.farm_type' => 'LSF']);
        $animalCount = new Expression('COUNT(DISTINCT ' . AnimalEvent::tableName() . '.animal_id) as animalCount');
        $milkCount = new Expression('COUNT(' . AnimalEvent::tableName() . '.id) as milkRecordsCount');
        $average = new Expression('AVG(JSON_EXTRACT(' . AnimalEvent::tableName() . '.additional_attributes, \'$."62"\')) as average');
        $query->addSelect([$animalCount, $milkCount, $average]);
        $query->groupBy(['animal.farm_id']);
        return new SqlDataProvider([
            'sql' => $query->createCommand()->rawSql,
            'pagination' => false,
            'sort' => false,
        ]);

    }

    public static function getAnimalsWithMilkingRecord($country_id = null)
    {
        $animals = AnimalEvent::find();
        $animals->innerJoin(Animal::tableName() . ' animal', 'animal.id=core_animal_event.animal_id');
        $animals->andWhere(['core_animal_event.event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $animals->andWhere(['core_animal_event.country_id' => $country_id]);
        return $animals->count('DISTINCT core_animal_event.animal_id');
    }

    public static function getFarmersWithAnimalsWithMilkingRecord($country_id = null)
    {
        $animals = Animal::find();
        $animals->joinWith('farm');
        $animals->innerJoin(AnimalEvent::tableName(), ' core_animal_event.animal_id=core_animal.id');
        $animals->andWhere([AnimalEvent::tableName() . '.event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $animals->andWhere(['core_animal.country_id' => $country_id]);
        return $animals->count('DISTINCT core_animal.farm_id');
    }
}