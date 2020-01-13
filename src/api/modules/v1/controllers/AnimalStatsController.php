<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Animal;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use backend\modules\reports\Constants;
use common\helpers\DateUtils;

class AnimalStatsController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Animal::class;

    public function init()
    {
        parent::init();
    }


    public function actionIndex($groupBy = Constants::ANIMAL_GRAPH_GROUP_BY_ANIMAL_TYPES, $country_id = null, $region_id = null, $district_id = null, $ward_id = null, $village_id = null, $animal_type = null, $breed = null, $from = null, $to = null)
    {
        $dateFilter = DateUtils::getDateFilterParams($from, $to, 'entry_date', false, false);
        $condition = $dateFilter['condition'];
        $params = [];
        $searchModel = Animal::searchModel([
            'defaultOrder' => ['id' => SORT_ASC],
            'condition' => $condition,
            'params' => $params,
            'enablePagination' => true,
            'pageSize' => SystemSettings::getPaginationSize(),
        ]);
        $searchModel->org_id = $country_id;
        $searchModel->region_id = $region_id;
        $searchModel->district_id = $district_id;
        $searchModel->ward_id = $ward_id;
        $searchModel->village_id = $village_id;
        $searchModel->animal_type = $animal_type;
        $searchModel->main_breed = $breed;


        $data = [];
        if ($groupBy == Constants::ANIMAL_GRAPH_GROUP_BY_ANIMAL_TYPES) {
            $animalTypes = Choices::getData('value, label', ['list_type_id' => ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES]);

            foreach ($animalTypes as $type) {
                $data['Animal Stats'][$type['label']] = [
                    'id' => $type['value'],
                ];
            }
            foreach ($data['Animal Stats'] as $typeLabel => $animalStats) {
                // get count of each animal type
                $typeId = $animalStats['id'];
                $searchModel->animal_type = $typeId;
                $count = $searchModel->search()->getTotalCount();
                $animalStats['count'] = $count;
                //$animalStats['query'] = $searchModel->search()->query->createCommand()->rawSql;
                $data['Animal Stats'][$typeLabel] = $animalStats;
            }
        } else {
            $breeds = Choices::getData('value, label', ['list_type_id' => ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS]);

            foreach ($breeds as $breed) {
                $data['Animal Stats'][$breed['label']] = [
                    'id' => $breed['value'],
                ];
            }
            foreach ($data['Animal Stats'] as $typeLabel => $animalStats) {
                // get count of each animal type
                $breedId = $animalStats['id'];
                $searchModel->main_breed = $breedId;
                $count = $searchModel->search()->getTotalCount();
                $animalStats['count'] = $count;
                //$animalStats['query'] = $searchModel->search()->query->createCommand()->rawSql;
                $data['Animal Stats'][$typeLabel] = $animalStats;
            }
        }


        return $data;
    }
}