<?php


namespace backend\modules\core\models;


use backend\modules\conf\Settings;
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

    public static function getLandingPageStats($org_id = null, $case = null)
    {
        if ($case == static::FARM) {
            return Farm::getCount(['org_id' => $org_id]);
        } else {
            return Animal::getCount(['org_id' => $org_id]);
        }
    }

    /**
     * @param $report_id
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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
        $cowsMilkingRecordsProvider = self::getGetAnimalsMilkingRecords($org_id);
        $cowsMilkingRecordsProvider->setPagination(false);
        $cowsMilkingRecords = $cowsMilkingRecordsProvider->getModels();
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
        $animalBox1 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_COW]);
        $animalBox2 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_HEIFER]);
        $animalBox3 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_BULL]);
        $animalBox4 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF]);
        $animalBox5 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF]);

        //3.Test Day boxes
        $testDayBox1 = MilkingReport::getFarmersWithAnimalsWithMilkingRecord($org_id);
        $testDayBox2 = MilkingReport::getAnimalsWithMilkingRecord($org_id);


        //4.Insemination,PD and Calving boxes
        $insBox1 = AnimalEvent::getCount(['org_id' => $org_id, 'event_type' => AnimalEvent::EVENT_TYPE_CALVING]);
        $insBox2 = AnimalEvent::getCount(['org_id' => $org_id, 'event_type' => AnimalEvent::EVENT_TYPE_AI]);
        $insBox3 = AnimalEvent::getCount(['org_id' => $org_id, 'event_type' => AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS]);
        $insBox4 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_MALE_CALF]);
        $insBox5 = Animal::getCount(['org_id' => $org_id, 'animal_type' => Animal::ANIMAL_TYPE_FEMALE_CALF]);

        $data = [];
        if ($report_id == static::FARMS_REGISTERED_REPORT) {
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
            $data[] = [
                'Charts' => [
                    'Large Scale Farms Grouped By Regions' => $LSFGroupedByRegions,
                    'LSF Animals By Breeds' => $LSFAnimalsGroupedByBreeds,
                ],
                'Table' => [
                    'title' =>  Lang::t('Test Day Milk in {country}', ['country' => $country]),
                    'data' => $LSFMilkRecords,
                ]
            ];
        } elseif ($report_id == static::TEST_DAY_REPORT) {
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
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
    public static function getLSFAnimalsGroupedByBreeds($org_id = null)
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    /**
     * @param null $org_id
     * @return array
     * @throws \Exception
     */
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

    public static function getGetAnimalsMilkingRecords($org_id = null)
    {
        $query = AnimalEvent::find();
        $query->addSelect('animal.name,animal.tag_id,animal.main_breed');
        $query->innerJoin(Animal::tableName() . ' animal', 'animal.id = core_animal_event.animal_id');
        $query->andWhere(['core_animal_event.event_type' => AnimalEvent::EVENT_TYPE_MILKING]);
        $query->andWhere(['core_animal_event.org_id' => $org_id]);
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