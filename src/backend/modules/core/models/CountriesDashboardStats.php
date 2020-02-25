<?php


namespace backend\modules\core\models;


use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use common\helpers\DbUtils;
use common\helpers\Lang;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\db\Expression;

class CountriesDashboardStats extends Model
{
    //model to get counts for
    CONST FARM = 1;
    CONST ANIMAL = 2;

    //Report IDs
    CONST FARMS_REGISTERED_REPORT = 1;
    CONST ANIMALS_REGISTERED_REPORT = 2;
    CONST LSF_FARM_STATS_REPORT = 3;
    CONST TEST_DAY_REPORT = 4;
    CONST INSEMINATION_PD_CALVING_REPORT = 5;
    CONST GENOTYPE_ANIMALS_REPORT = 6;

    public static function getLandingPageStats($country_id = null, $case = null)
    {
        if ($case == static::FARM) {
            return Farm::getCount(['country_id' => $country_id]);
        } else {
            return Animal::getCount(['country_id' => $country_id]);
        }
    }

    /**
     * @param $report_id
     * @param null $country_id
     * @return array
     * @throws \Exception
     */
    public static function getCountryReports($report_id, $country_id = null)
    {
        $country = Country::getScalar('name', ['id' => $country_id]);
        $data = [];
        if ($report_id == static::FARMS_REGISTERED_REPORT) {
            //1. charts
            $farmsGroupedByRegions = self::getFarmsGroupedByRegions($country_id);
            $farmsGroupedByFarmType = self::getFarmsGroupedByFarmType($country_id);

            //2.Farm boxes
            $farmBox1 = Farm::getCount(['country_id' => $country_id]);
            $farmBox2 = Farm::find()->andFilterWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 1])->andFilterWhere(['country_id' => $country_id])->count();
            $farmBox3 = Farm::find()->andFilterWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => 2])->andFilterWhere(['country_id' => $country_id])->count();
            $farmBox4 = Farm::find()->andFilterWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => [1, 2]])->andFilterWhere(['country_id' => $country_id])->count();
            $data[] = [
                'Charts' => [
                    'Farms Grouped By Regions' => $farmsGroupedByRegions,
                    'Farms Grouped By Farm Types' => $farmsGroupedByFarmType
                ],
                'Boxes' => [
                    'No of farms' => $farmBox1,
                    'Male House Hold Head' => $farmBox2,
                    'Female House Hold Head' => $farmBox3,
                    'House Holds Headed By both male and female' => $farmBox4,
                ],
            ];
        } elseif ($report_id == static::ANIMALS_REGISTERED_REPORT) {
            //1.charts
            $animalsGroupedByRegions = self::getAnimalsGroupedByRegions($country_id);
            $animalsGroupedByBreeds = self::getAnimalsGroupedByBreeds($country_id);

            //2.Animal boxes
            $animalBox1 = Animal::getCount(['country_id' => $country_id, 'animal_type' => Animal::ANIMAL_TYPE_COW]);
            $animalBox2 = Animal::getCount(['country_id' => $country_id, 'animal_type' => Animal::ANIMAL_TYPE_HEIFER]);
            $animalBox3 = Animal::getCount(['country_id' => $country_id, 'animal_type' => Animal::ANIMAL_TYPE_BULL]);
            $animalBox4 = Animal::getCount(['country_id' => $country_id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF]);
            $animalBox5 = Animal::getCount(['country_id' => $country_id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF]);
            $data[] = [
                'Charts' => [
                    'Animals Grouped By Regions' => $animalsGroupedByRegions,
                    'Animals Grouped By Breeds' => $animalsGroupedByBreeds,
                ],
                'Boxes' => [
                    'Cows' => $animalBox1,
                    'Heifers' => $animalBox2,
                    'Bulls' => $animalBox3,
                    'MaleCalves' => $animalBox4,
                    'FemaleCalves' => $animalBox5,
                ],
            ];
        } elseif ($report_id == static::LSF_FARM_STATS_REPORT) {
            //charts
            $LSFGroupedByRegions = self::getLSFGroupedByRegions($country_id);
            $LSFAnimalsGroupedByBreeds = self::getLSFAnimalsGroupedByBreeds($country_id);

            //table
            $LSFMilkRecordsProvider = MilkingReport::getLargeScaleFarmMilkDetails($country_id);
            $LSFMilkRecordsProvider->setPagination(false);
            $LSFMilkRecords = $LSFMilkRecordsProvider->getModels();
            $data[] = [
                'Charts' => [
                    'Large Scale Farms Grouped By Regions' => $LSFGroupedByRegions,
                    'LSF Animals By Breeds' => $LSFAnimalsGroupedByBreeds,
                ],
                'Table' => [
                    'title' => Lang::t('Test Day Milk in {country}', ['country' => $country]),
                    'data' => $LSFMilkRecords,
                ]
            ];
        } elseif ($report_id == static::TEST_DAY_REPORT) {
            //charts
            $testDayMilkGroupedByRegions = self::getTestDayMilkGroupedByRegions($country_id);
            //table
            // $cowsMilkingRecordsProvider = self::getGetAnimalsMilkingRecords($country_id);
            //$cowsMilkingRecordsProvider->setPagination(false);
            //$cowsMilkingRecords = $cowsMilkingRecordsProvider->getModels();

            //3.Test Day boxes
            $testDayBox1 = MilkingReport::getFarmersWithAnimalsWithMilkingRecord($country_id);
            $testDayBox2 = MilkingReport::getAnimalsWithMilkingRecord($country_id);
            $data[] = [
                'Charts' => [
                    'Test Day Grouped By regions' => $testDayMilkGroupedByRegions,
                ],
                'Boxes' => [
                    'Farmers With Animals With Test Day' => $testDayBox1,
                    'Animals With Test Day' => $testDayBox2,
                ],
                //'Table' => [
                // 'title' =>  Lang::t('Cows Milking Records in {country}', ['country' => $country]),
                // 'data' => $cowsMilkingRecords,
                //]
            ];
        } elseif ($report_id == static::INSEMINATION_PD_CALVING_REPORT) {
            //charts
            $maleCalvesByRegions = self::getMaleCalvesByRegions($country_id);
            $femaleCalvesByRegions = self::getFemaleCalvesByRegions($country_id);

            //Insemination,PD and Calving boxes
            $insBox1 = AnimalEvent::getCount(['country_id' => $country_id, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING]);
            $insBox2 = AnimalEvent::getCount(['country_id' => $country_id, 'event_type' => AnimalEvent::EVENT_TYPE_AI]);
            $insBox3 = AnimalEvent::getCount(['country_id' => $country_id, 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS]);
            $insBox4 = Animal::getCount(['country_id' => $country_id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF]);
            $insBox5 = Animal::getCount(['country_id' => $country_id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF]);

            $data[] = [
                'Charts' => [
                    'Male Calves Grouped By Regions' => $maleCalvesByRegions,
                    'Female Calves Grouped By Regions' => $femaleCalvesByRegions,
                ],
                'Boxes' => [
                    'Calving' => $insBox1,
                    'Insemination' => $insBox2,
                    'PD' => $insBox3,
                    'Male Calves' => $insBox4,
                    'Female Calves' => $insBox5,
                ]
            ];
        } elseif ($report_id == static::GENOTYPE_ANIMALS_REPORT) {
            $data[] = [];
        }
        return $data;
    }

    /**
     * @param null $country_id
     * @return array
     * @throws \Exception
     */
    public static function getFarmsGroupedByRegions($country_id = null)
    {

        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
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

    public static function getFarmsGroupedByDistricts($country_id = null, $region_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get districts
        $districts = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_DISTRICT]);
        foreach ($districts as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('district_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['region_id' => $region_id])
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

    public static function getFarmsGroupedByWards($country_id = null, $district_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get wards
        $wards = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_WARD]);
        foreach ($wards as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('ward_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['district_id' => $district_id])
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

    public static function getFarmsGroupedByVillages($country_id = null, $ward_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get villages
        $villages = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_VILLAGE]);
        foreach ($villages as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('village_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['ward_id' => $ward_id])
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


    public static function getFarmsGroupedByFarmType($country_id = null, $param = [])
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
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere($param)
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

    /**
     * @param null $country_id
     * @return array
     * @throws \Exception
     */
    public static function getAnimalsGroupedByRegions($country_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);


            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
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

    public static function getAnimalsGroupedByDistricts($country_id = null, $region_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get districts
        $districts = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_DISTRICT]);
        foreach ($districts as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('district_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['region_id' => $region_id])
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

    public static function getAnimalsGroupedByWards($country_id = null, $district_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get wards
        $wards = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_WARD]);
        foreach ($wards as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('ward_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['district_id' => $district_id])
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

    public static function getAnimalsGroupedByVillages($country_id = null, $ward_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get villages
        $villages = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_VILLAGE]);
        foreach ($villages as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('village_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['ward_id' => $ward_id])
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

    /**
     * @param null $country_id
     * @param array $param
     * @return array
     * @throws \Exception
     */
    public static function getAnimalsGroupedByBreeds($country_id = null, $param = [])
    {
        $condition = '';
        $params = [];
        //list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get breeds
        $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
        foreach ($breeds as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere($param)
                //  ->andFilterWhere([Farm::tableName() . '.field_agent_id' => $field_agent_id])
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

    /**
     * @param null $country_id
     * @return array
     * @throws \Exception
     */
    public static function getLSFGroupedByRegions($country_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        //print_r($regions);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Farm::find()->where($newcondition, $newparams)
                ->andFilterWhere(['farm_type' => 'LSF', 'country_id' => $country_id])
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

    public static function getLSFGroupedByDistricts($country_id = null, $region_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get districts
        $districts = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_DISTRICT]);
        foreach ($districts as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('district_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['farm_type' => 'LSF', 'country_id' => $country_id])
                ->andFilterWhere(['region_id' => $region_id])
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

    public static function getLSFGroupedByWards($country_id = null, $district_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get wards
        $wards = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_WARD]);
        foreach ($wards as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('ward_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['farm_type' => 'LSF', 'country_id' => $country_id])
                ->andFilterWhere(['district_id' => $district_id])
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

    public static function getLSFGroupedByVillages($country_id = null, $ward_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get villages
        $villages = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_VILLAGE]);
        foreach ($villages as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('village_id', $id, $condition, $params);

            $count = Farm::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['farm_type' => 'LSF', 'country_id' => $country_id])
                ->andFilterWhere(['ward_id' => $ward_id])
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


    public static function getLSFAnimalsGroupedByBreeds($country_id = null, $param = [])
    {
        $condition = '';
        $params = [];
        //  list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get breeds
        $breeds = Choices::getList(\backend\modules\core\models\ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
        foreach ($breeds as $id => $label) {
            list($newCondition, $newParams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);
            $count = Animal::find()->joinWith('farm')
                ->andWhere($newCondition, $newParams)
                ->andFilterWhere([Farm::tableName() . '.farm_type' => 'LSF'])
                ->andFilterWhere([Farm::tableName() . '.country_id' => $country_id])
                ->andFilterWhere($param)
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

    /**
     * @param null $country_id
     * @return array
     * @throws \Exception
     */
    public static function getTestDayMilkGroupedByRegions($country_id = null)
    {
        $condition = '';
        $params = [];
        //list($condition, $params) = AnimalEvent::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = AnimalEvent::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'country_id' => $country_id])
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

    public static function getTestDayMilkGroupedByDistricts($country_id = null, $region_id = null)
    {
        $condition = '';
        $params = [];
        //list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get districts
        $districts = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_DISTRICT]);
        foreach ($districts as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('district_id', $id, $condition, $params);

            $count = AnimalEvent::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'country_id' => $country_id])
                ->andFilterWhere(['region_id' => $region_id])
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

    public static function getTestDayMilkGroupedByWards($country_id = null, $district_id = null)
    {
        $condition = '';
        $params = [];
        //list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get wards
        $wards = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_WARD]);
        foreach ($wards as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('ward_id', $id, $condition, $params);

            $count = AnimalEvent::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'country_id' => $country_id])
                ->andFilterWhere(['district_id' => $district_id])
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

    public static function getTestDayMilkGroupedByVillages($country_id = null, $ward_id = null)
    {
        $condition = '';
        $params = [];
        //list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get villages
        $villages = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_VILLAGE]);
        foreach ($villages as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('village_id', $id, $condition, $params);

            $count = AnimalEvent::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['event_type' => AnimalEvent::EVENT_TYPE_MILKING, 'country_id' => $country_id])
                ->andFilterWhere(['ward_id' => $ward_id])
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

    /**
     * @param null $country_id
     * @return array
     * @throws \Exception
     */
    public static function getMaleCalvesByRegions($country_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
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

    public static function getMaleCalvesGroupedByDistricts($country_id = null, $region_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get districts
        $districts = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_DISTRICT]);
        foreach ($districts as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('district_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['region_id' => $region_id])
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

    public static function getMaleCalvesGroupedByWards($country_id = null, $district_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get wards
        $wards = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_WARD]);
        foreach ($wards as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('ward_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['district_id' => $district_id])
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

    public static function getMaleCalvesGroupedByVillages($country_id = null, $ward_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get villages
        $villages = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_VILLAGE]);
        foreach ($villages as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('village_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_MALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['ward_id' => $ward_id])
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

    /**
     * @param null $country_id
     * @return array
     * @throws \Exception
     */
    public static function getFemaleCalvesByRegions($country_id = null)
    {

        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
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

    public static function getFemaleCalvesGroupedByDistricts($country_id = null, $region_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get districts
        $districts = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_DISTRICT]);
        foreach ($districts as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('district_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['region_id' => $region_id])
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

    public static function getFemaleCalvesGroupedByWards($country_id = null, $district_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get wards
        $wards = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_WARD]);
        foreach ($wards as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('ward_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['district_id' => $district_id])
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

    public static function getFemaleCalvesGroupedByVillages($country_id = null, $ward_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get villages
        $villages = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_VILLAGE]);
        foreach ($villages as $id => $label) {
            list($newcondition, $newparams) = DbUtils::appendCondition('village_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newcondition, $newparams)
                ->andFilterWhere(['animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF])
                ->andFilterWhere(['country_id' => $country_id])
                ->andFilterWhere(['ward_id' => $ward_id])
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

    public static function getGetAnimalsMilkingRecords($country_id = null, $params = [])
    {
        $query = AnimalEvent::find();
        $query->addSelect('animal.name,animal.tag_id,animal.main_breed');
        $query->innerJoin(Animal::tableName() . ' animal', 'animal.id = core_animal_event.animal_id');
        $query->andFilterWhere(['core_animal_event.event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $query->andFilterWhere(['core_animal_event.country_id' => $country_id]);
        $query->andFilterWhere($params);

        $milkCount = new Expression('COUNT(' . AnimalEvent::tableName() . '.id) as milkRecordsCount');
        $average = new Expression('AVG(JSON_EXTRACT(' . AnimalEvent::tableName() . '.additional_attributes, \'$."62"\')) as average');
        $query->addSelect([$milkCount, $average]);
        $query->groupBy(['animal.id']);
        return new SqlDataProvider([
            'sql' => $query->createCommand()->rawSql,
            'pagination' => [
                'pageSize' => SystemSettings::getPaginationSize(),
            ],
            'sort' => false,
        ]);
    }
}