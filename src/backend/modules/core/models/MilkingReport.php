<?php

namespace backend\modules\core\models;

use backend\modules\auth\Session;
use common\helpers\DbUtils;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\db\Expression;

class MilkingReport extends Model
{
    public static function getLargeScaleFarmMilkDetails($country_id = null, $params = [])
    {
        $query = AnimalEvent::find();
        $query->addSelect('farm.farmer_name');
        $query->innerJoin(Animal::tableName() . ' animal', 'animal.id = core_animal_event.animal_id');
        $query->innerJoin(Farm::tableName() . ' farm', 'farm.id = animal.farm_id');
        $query->andFilterWhere(['core_animal_event.event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $query->andFilterWhere(['core_animal_event.country_id' => $country_id]);
        $query->andFilterWhere($params);


        $query->andFilterWhere(['farm.farm_type' => 'LSF']);
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

    public static function getTestDayRecord($country_id = null, $caseAnimal = true)
    {
        $animals = AnimalEvent::find();
        $animals->innerJoin(Animal::tableName() . ' animal', 'animal.id=core_animal_event.animal_id');
        $animals->andFilterWhere(['core_animal_event.event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $animals->andFilterWhere(['core_animal_event.country_id' => $country_id]);
        if (Session::isVillageUser()) {
            if (Session::isFieldAgent()) {
                $animals->andFilterWhere(['core_animal_event.village_id' => Session::getVillageId(), 'core_animal_event.field_agent_id' => Session::getUserId()]);
            } else {
                $animals->andFilterWhere(['core_animal_event.village_id' => Session::getVillageId()]);
            }
        } elseif (Session::isWardUser()) {
            if (Session::isFieldAgent()) {
                $animals->andFilterWhere(['core_animal_event.ward_id' => Session::getWardId(), 'core_animal_event.field_agent_id' => Session::getUserId()]);
            } else {
                $animals->andFilterWhere(['core_animal_event.ward_id' => Session::getWardId()]);
            }
        } elseif (Session::isDistrictUser()) {
            if (Session::isFieldAgent()) {
                $animals->andFilterWhere(['core_animal_event.district_id' => Session::getDistrictId(), 'core_animal_event.field_agent_id' => Session::getUserId()]);
            } else {
                $animals->andFilterWhere(['core_animal_event.district_id' => Session::getDistrictId()]);
            }
        } elseif (Session::isRegionUser()) {
            if (Session::isFieldAgent()) {
                $animals->andFilterWhere(['core_animal_event.region_id' => Session::getRegionId(), 'core_animal_event.field_agent_id' => Session::getUserId()]);
            } else {
                $animals->andFilterWhere(['core_animal_event.region_id' => Session::getRegionId()]);
            }
        } elseif (Session::isCountryUser()) {
            if (Session::isFieldAgent()) {
                $animals->andFilterWhere(['core_animal_event.field_agent_id' => Session::getUserId()]);
            }
        }
        if ($caseAnimal == true) {
            return $animals->count('DISTINCT core_animal_event.animal_id');
        } else {
            return $animals->count('DISTINCT animal.farm_id');
        }
    }
}