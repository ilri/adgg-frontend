<?php


namespace backend\modules\core\models;


use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use common\helpers\DbUtils;
use common\helpers\Lang;
use yii\base\Model;

class CountriesDashboardStats extends Model
{
    //model to get counts for
    CONST FARM = 1;
    CONST ANIMAL = 2;

    //Report names
    CONST FARMS_REGISTERED_REPORT = 1;
    CONST ANIMALS_REGISTERED_REPORT = 2;
    CONST LSF_FARM_STATS_REPORT = 3;
    CONST TEST_DAY_REPORT = 4;
    CONST INSEMINATION_PD_CALVING_REPORT = 5;
    CONST GENOTYPE_ANIMALS_REPORT = 6;

    public static function getLandingPageStats($org_id = null, $case = null)
    {
        if ($case == static::FARM) {
            return Farm::getCount(['org_id' => $org_id]);
        } else {
            return Animal::getCount(['org_id' => $org_id]);
        }
    }

    public static function getCountryReports($report_id, $org_id = null)
    {
        $country = Organization::getScalar('name', ['id' => $org_id]);
        //charts
        $farmsGroupedByRegions = self::getFarmsGroupedByRegions($org_id);
        $farmsGroupedByFarmType = self::getFarmsGroupedByFarmType($org_id);
        $animalsGroupedByRegions = self::getAnimalsGroupedByRegions($org_id);
        $animalsGroupedByBreeds = self::getAnimalsGroupedByBreeds($org_id);
        $LSFGroupedByRegions = self::getLSFGroupedByRegions($org_id);
        $LSFAnimalsGroupedByBreeds = self::getLSFAnimalsGroupedByBreeds($org_id);
        $LSFMilkRecordsProvider = MilkingReport::getLargeScaleFarmMilkDetails($org_id);
        $LSFMilkRecordsProvider->setPagination(false);
        $LSFMilkRecords = $LSFMilkRecordsProvider->getModels();
        $testDayMilkGroupedByRegions = self::getTestDayMilkGroupedByRegions($org_id);
        $maleCalvesByRegions = self::getMaleCalvesByRegions($org_id);
        $femaleCalvesByRegions = self::getFemaleCalvesByRegions($org_id);

        //boxes
        //1.Farm boxes
        $farmBox1 = Farm::getCount(['org_id' => $org_id]);
        $farmBox2 = Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 1])->andWhere(['org_id' => $org_id])->count();
        $farmBox3 = Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 2])->andWhere(['org_id' => $org_id])->count();
        $farmBox4 = Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => [1, 2]])->andWhere(['org_id' => $org_id])->count();

        //2.Animal boxes
        $animalTypesData = [];
        $animalTypes = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES);
        foreach ($animalTypes as $animalType => $label) {
            $count = Animal::getCount(['org_id' => $org_id, 'animal_type' => $animalType]);
            if ($count > 0) {
                $animalTypesData[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };

        //3.Test Day boxes
        $testDayBox1 = MilkingReport::getFarmersWithAnimalsWithMilkingRecord($org_id);
        $testDayBox2 = MilkingReport::getAnimalsWithMilkingRecord($org_id);


        //4.Insemination,PD and Calving boxes
        $eventTypesData = [];
        $eventTypes = AnimalEvent::eventTypeOptions();
        foreach ($eventTypes as $type => $label) {
            $count = AnimalEvent::getCount(['org_id' => $org_id, 'event_type' => $type]);
            if ($type == AnimalEvent::EVENT_TYPE_AI || $type == AnimalEvent::EVENT_TYPE_CALVING || $type == AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS) {
                $eventTypesData[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        $insBox4 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF]);
        $insBox5 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF]);

        $testCalve = [
            ['label' => 'Male Calves Count', 'value' => $insBox4],
            ['label' => 'Female Calves Count', 'value' => $insBox5],
        ];

        $data = [];
        if ($report_id == static::FARMS_REGISTERED_REPORT) {
            $data[] = [
                'Charts' => [
                    [
                        'title' =>  Lang::t('Farms Grouped By Regions in {country}', ['country' => $country]),
                        'data' => $farmsGroupedByRegions,
                    ],
                    [
                        'title' =>  Lang::t( 'Farms Grouped By Farm Types in {country}', ['country' => $country]),
                        'data' => $farmsGroupedByFarmType,
                    ],
                ],
                'Boxes' => [
                    ['label' => 'No of farms', 'value' => $farmBox1],
                    ['label' => 'Male House Hold Head' , 'value' => $farmBox2],
                    ['label' => 'Female House Hold Head', 'value' =>  $farmBox3],
                    ['label' => 'House Holds Headed By both male and female' , 'value' => $farmBox4]
                ],
            ];
        } elseif ($report_id == static::ANIMALS_REGISTERED_REPORT) {
            $data[] = [
                'Charts' => [
                    [
                        'title' =>  Lang::t('Animals Grouped By Regions in {country}', ['country' => $country]),
                        'data' => $animalsGroupedByRegions,
                    ],
                    [
                        'title' =>  Lang::t( 'Animals Grouped By Breeds in {country}', ['country' => $country]),
                        'data' => $animalsGroupedByBreeds,
                    ],
                ],
                'Boxes' =>
                    $animalTypesData,
            ];
        } elseif ($report_id == static::LSF_FARM_STATS_REPORT) {
            $data[] = [
                'Charts' => [
                    [
                        'title' =>  Lang::t('Large Scale Farms Grouped By Regions in {country}', ['country' => $country]),
                        'data' => $LSFGroupedByRegions,
                    ],
                    [
                        'title' =>  Lang::t( 'LSF Animals By Breeds in {country}', ['country' => $country]),
                        'data' => $LSFAnimalsGroupedByBreeds,
                    ],
                ],
                'Table' => [
                    'title' =>  Lang::t('Test Day Milk in {country}', ['country' => $country]),
                    'data' => $LSFMilkRecords,
                ]
            ];
        } elseif ($report_id == static::TEST_DAY_REPORT) {
            $data[] = [
                'Charts' => [
                    [
                        'title' =>  Lang::t('Test Day Grouped By regions in {country}', ['country' => $country]),
                        'data' => $testDayMilkGroupedByRegions,
                    ],
                ],
                'Boxes' => [
                    [
                        'label' => 'Farmers With Animals With Test Day',
                        'value' => $testDayBox1
                    ],
                    [
                        'label' => 'Animals With Test Day',
                        'value' => $testDayBox2
                    ],
                ]
            ];
        } elseif ($report_id == static::INSEMINATION_PD_CALVING_REPORT) {
            $data[] = [
                'Charts' => [
                    [
                        'title' =>  Lang::t('Male Calves Grouped By Regions in {country}', ['country' => $country]),
                        'data' => $maleCalvesByRegions,
                    ],
                    [
                        'title' =>  Lang::t( 'Female Calves Grouped By Regions in {country}', ['country' => $country]),
                        'data' => $femaleCalvesByRegions,
                    ],
                ],
                'Boxes' => array_merge($eventTypesData, $testCalve)
            ];
        } elseif ($report_id == static::GENOTYPE_ANIMALS_REPORT) {
            $data[] = [];
        }
        return $data;
    }

    public static function getFarmsGroupedByRegions($org_id = null)
    {
        
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getFarmsGroupedByFarmType($org_id = null)
    {
        

        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get farm types
        $farmTypes = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_FARM_TYPE);
        //print_r($farmTypes);
        foreach ($farmTypes as $type => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('farm_type', $type, $condition, $params);
            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getAnimalsGroupedByRegions($org_id = null)
    {
        
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getAnimalsGroupedByBreeds($org_id = null)
    {
        

        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get breeds
        $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
        foreach ($breeds as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getLSFGroupedByRegions($org_id = null)
    {
        
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        //print_r($regions);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Farm::find()->where($newcondition, $newparams)
                ->andFilterWhere(['farm_type' => 'LSF', 'org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }

        };
        return $data;

    }

    public static function getLSFAnimalsGroupedByBreeds($org_id = null)
    {
        
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get breeds
        $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
        foreach ($breeds as $id => $label) {
            list($newCondition, $newParams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);
            $count = Animal::find()->joinWith('farm')
                ->andWhere($newCondition, $newParams)
                ->andWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                ->andFilterWhere([Farm::tableName() . '.org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval($count)
                ];
            }

        };
        return $data;
    }

    public static function getTestDayMilkGroupedByRegions($org_id = null)
    {
        
        $condition = '';
        $params = [];
        list($condition, $params) = AnimalEvent::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = AnimalEvent::find()->where($newcondition, $newparams)
                ->andFilterWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }

        };
        return $data;

    }

    public static function getMaleCalvesByRegions($org_id = null)
    {
        
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Animal::find()->where($newcondition, $newparams)
                ->andWhere(['animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])
                ->andFilterWhere(['org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getFemaleCalvesByRegions($org_id = null)
    {
        
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Animal::find()->where($newcondition, $newparams)
                ->andWhere(['animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])
                ->andFilterWhere(['org_id' => $org_id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'label' => $label,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }
}