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
        $p_data = [];
        if ($groupBy == Constants::ANIMAL_GRAPH_GROUP_BY_ANIMAL_TYPES) {
            $animalTypes = Choices::getData('value, label', ['list_type_id' => ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES]);

            foreach ($animalTypes as $type) {
                $p_data[$type['value']] = [
                    'id' => $type['value'],
                    'label' => $type['label'],
                ];
            }

            foreach ($p_data as $typeId => $animalStats) {
                $searchModel->animal_type = $typeId;
                $count = $searchModel->search()->getTotalCount();
                $animalStats['count'] = $count;
                $data[] = $animalStats;
            }
        } else {
            $breeds = Choices::getData('value, label', ['list_type_id' => ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS]);

            foreach ($breeds as $type) {
                $p_data[$type['value']] = [
                    'id' => $type['value'],
                    'label' => $type['label'],
                ];
            }
            foreach ($p_data as $typeId => $animalStats) {
                $searchModel->main_breed = $typeId;
                $count = $searchModel->search()->getTotalCount();
                $animalStats['count'] = $count;
                $data[] = $animalStats;
            }
        }


        return $data;
    }
}