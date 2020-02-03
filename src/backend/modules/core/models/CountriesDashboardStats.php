<?php


namespace backend\modules\core\models;


use backend\modules\core\models\Animal;
use backend\modules\core\models\Farm;
use common\helpers\DbUtils;
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

    public static function getCountryReports($report_id = null, $org_id = null)
    {
        $data = [];
        //charts
        $farmsGroupedByRegions = self::getFarmsGroupedByRegions($org_id);
        $farmsGroupedByFarmType = self::getFarmsGroupedByFarmType($org_id);
        $animalsGroupedByRegions = self::getAnimalsGroupedByRegions($org_id);
        $animalsGroupedByBreeds = self::getAnimalsGroupedByBreeds($org_id);
        $LSFGroupedByRegions = self::getLSFGroupedByRegions($org_id);
        $LSFAnimalsGroupedByBreeds = self::getLSFAnimalsGroupedByBreeds($org_id);
        $testDayMilkGroupedByRegions = self::getTestDayMilkGroupedByRegions($org_id);
        $maleCalvesByRegions = self::getMaleCalvesByRegions($org_id);
        $femaleCalvesByRegions = self::getFemaleCalvesByRegions($org_id);

        //boxes
        //1.Farm boxes
        $farmBox1 = Farm::getCount(['org_id' => $org_id]);
        $farmBox2 = Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 1])->andWhere(['org_id' => $org_id])->count();
        $farmBox3 = Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 2])->andWhere(['org_id' => $org_id])->count();
        $farmBox4 = Farm::find()->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => [1, 2]])->andWhere(['org_id' => $org_id])->count();
        $farmBoxes = [];
        $farmBoxes[] = [
            'No of farms' => [$farmBox1],
            'Male House Hold Head' => [$farmBox2],
            'Female House Hold Head' => [$farmBox3],
            'House Holds Headed By both male and female' => [$farmBox4]
        ];
        //2.Animal boxes
        $animalTypesData = [];
        $animalTypes = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES);
        foreach ($animalTypes as $animalType => $label) {
            $count = Animal::getCount(['org_id' => $org_id, 'animal_type' => $animalType]);
            if ($count > 0) {
                $animalTypesData[] = [
                    'Animal Type' => $label,
                    'Number' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };

        //3.Test Day boxes
        $testDayBox1 = MilkingReport::getFarmersWithAnimalsWithMilkingRecord($org_id);
        $testDayBox2 = MilkingReport::getAnimalsWithMilkingRecord($org_id);

        $testDayBoxes = [];
        $testDayBoxes[] = [
            'Farmers With Animals With Test Day' => [$testDayBox1],
            'Animals With Test Day' => [$testDayBox2],
        ];

        //4.Insemination,PD and Calving boxes
        $eventTypesData = [];
        $eventTypes = AnimalEvent::eventTypeOptions();
        foreach ($eventTypes as $type => $label) {
            $count = AnimalEvent::getCount(['org_id' => $org_id, 'event_type' => $type]);
            if ($type == AnimalEvent::EVENT_TYPE_AI || $type == AnimalEvent::EVENT_TYPE_CALVING || $type == AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS) {
                $eventTypesData[] = [
                    'Event Type' => $label,
                    'Number' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        $insBox4 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF]);
        $insBox5 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF]);

        $testCalve = [];
        $testCalve[] = [
            'Male Calves Count' => $insBox4,
            'Female Calves Count' => $insBox5,
        ];

        if ($report_id == static::FARMS_REGISTERED_REPORT) {
            $data[] = [
                'Charts' => [
                    'Farms Grouped By Regions' => [
                        $farmsGroupedByRegions,
                    ],
                    'Farms Grouped By Farm Types' => [
                        $farmsGroupedByFarmType,
                    ]
                ],
                'Boxes' => [
                    'List Of Farm Boxes' => $farmBoxes,
                ]
            ];
        } elseif ($report_id == static::ANIMALS_REGISTERED_REPORT) {
            $data[] = [
                'Charts' => [
                    'Animals Grouped By Regions' => [
                        $animalsGroupedByRegions,
                    ],
                    'Animals Grouped By Breeds' => [
                        $animalsGroupedByBreeds,
                    ]
                ],
                'Boxes' => [
                    'List Of Animal Boxes' => $animalTypesData,
                ]
            ];
        } elseif ($report_id == static::LSF_FARM_STATS_REPORT) {
            $data[] = [
                'Charts' => [
                    'Large Scale Farms Grouped By Regions' => [
                        $LSFGroupedByRegions,
                    ],
                    'LSF Animals By Breeds' => [
                        $LSFAnimalsGroupedByBreeds,
                    ]
                ],
            ];
        } elseif ($report_id == static::TEST_DAY_REPORT) {
            $data[] = [
                'Charts' => [
                    'Test Day Grouped By regions' => [
                        $testDayMilkGroupedByRegions,
                    ],
                ],
                'Boxes' => [
                    'List Of Test Day Boxes' => $testDayBoxes
                ]
            ];
        } elseif ($report_id == static::GENOTYPE_ANIMALS_REPORT) {
            $data[] = [
            ];
        } elseif ($report_id == static::INSEMINATION_PD_CALVING_REPORT) {
            $data[] = [
                'Charts' => [
                    'Male Calves Grouped By Regions' => [
                        $maleCalvesByRegions,
                    ],
                    'Female Calves Grouped By Regions' => [
                        $femaleCalvesByRegions,
                    ]
                ],
                'Boxes' => [
                    'List Of Insemination, PD And Calving Boxes' => array_merge($eventTypesData, $testCalve),
                ]
            ];

        }
        return $data;
    }

    public static function getFarmsGroupedByRegions($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andWhere(['org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Region Name' => $label,
                    'No Of Farms' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getFarmsGroupedByFarmType($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);

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
                ->andWhere(['org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Farm Type' => $label,
                    'Number' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getAnimalsGroupedByRegions($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andWhere(['org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Region Name' => $label,
                    'No Of Animals' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getAnimalsGroupedByBreeds($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);

        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get breeds
        $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
        foreach ($breeds as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andWhere(['org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Breed Name' => $label,
                    'Number' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getLSFGroupedByRegions($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
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
                ->andWhere(['farm_type' => 'LSF', 'org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Region Name' => $label,
                    'Number' => floatval(number_format($count, 2, '.', '')),
                ];
            }

        };
        return $data;

    }

    public static function getLSFAnimalsGroupedByBreeds($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
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
                ->andWhere([Farm::tableName() . '.org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Breed' => $label,
                    'Number' => floatval($count)];
            }

        };
        return $data;
    }

    public static function getTestDayMilkGroupedByRegions($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
        $condition = '';
        $params = [];
        list($condition, $params) = AnimalEvent::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = OrganizationUnits::getListData('id', 'name', '', ['level' => OrganizationUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = AnimalEvent::find()->where($newcondition, $newparams)
                ->andWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Region Name' => $label,
                    'Number' => floatval(number_format($count, 2, '.', '')),
                ];
            }

        };
        return $data;

    }

    public static function getMaleCalvesByRegions($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
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
                ->andWhere(['org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Region Name' => $label,
                    'Number' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getFemaleCalvesByRegions($org_id = null)
    {
        $country = Organization::findOne(['id' => $org_id]);
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
                ->andWhere(['org_id' => $country->id])
                ->count();
            if ($count > 0) {
                $data[] = [
                    'Region Name' => $label,
                    'Value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }
}