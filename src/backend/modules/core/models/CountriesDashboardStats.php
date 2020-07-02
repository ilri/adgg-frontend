<?php


namespace backend\modules\core\models;


use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use common\helpers\ArrayHelper;
use common\helpers\DateUtils;
use common\helpers\DbUtils;
use common\helpers\Lang;
use Yii;
use yii\base\Model;
use yii\data\SqlDataProvider;
use yii\db\Expression;
use yii\db\Query;

class CountriesDashboardStats extends Model
{
    //Quick Reports ID
    CONST FARMS_REGISTERED_REPORT = 1;
    CONST ANIMALS_REGISTERED_REPORT = 2;
    CONST LSF_FARM_STATS_REPORT = 3;
    CONST TEST_DAY_REPORT = 4;
    CONST INSEMINATION_PD_CALVING_REPORT = 5;
    CONST GENOTYPE_ANIMALS_REPORT = 6;

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
            $data[] = [
                'Charts' => [
                    'Farms Grouped By Regions' => self::getFarmsGroupedByRegions($country_id),
                    'Farms Grouped By Farm Types' => self::getFarmsGroupedByFarmType($country_id),
                ],
                'Boxes' => [
                    'No of farms' => self::getFarmCounts($country_id),
                    'Male House Hold Head' => self::getFarmCounts($country_id, true, 1),
                    'Female House Hold Head' => self::getFarmCounts($country_id, true, 2),
                    'House Holds Headed By both male and female' => self::getFarmCounts($country_id, true, [1, 2]),
                ],
            ];
        } elseif ($report_id == static::ANIMALS_REGISTERED_REPORT) {
            $data[] = [
                'Charts' => [
                    'Animals Grouped By Regions' => self::getAnimalsGroupedByRegions($country_id),
                    'Animals Grouped By Breeds' => self::getAnimalsGroupedByBreeds($country_id),
                ],
                'Boxes' => [
                    'Cows' => self::getAnimalCounts($country_id, Animal::ANIMAL_TYPE_COW),
                    'Heifers' => self::getAnimalCounts($country_id, Animal::ANIMAL_TYPE_HEIFER),
                    'Bulls' => self::getAnimalCounts($country_id, Animal::ANIMAL_TYPE_BULL),
                    'MaleCalves' => self::getAnimalCounts($country_id, Animal::ANIMAL_TYPE_MALE_CALF),
                    'FemaleCalves' => self::getAnimalCounts($country_id, Animal::ANIMAL_TYPE_FEMALE_CALF),
                ],
            ];
        } elseif ($report_id == static::LSF_FARM_STATS_REPORT) {
            //table
            $LSFMilkRecordsProvider = MilkingReport::getLargeScaleFarmMilkDetails($country_id);
            $LSFMilkRecordsProvider->setPagination(false);
            $LSFMilkRecords = $LSFMilkRecordsProvider->getModels();
            $data[] = [
                'Charts' => [
                    'Large Scale Farms Grouped By Regions' => self::getLSFGroupedByRegions($country_id),
                    'LSF Animals By Breeds' => self::getLSFAnimalsGroupedByBreeds($country_id),
                ],
                'Table' => [
                    'title' => Lang::t('Test Day Milk in {country}', ['country' => $country]),
                    'data' => $LSFMilkRecords,
                ]
            ];
        } elseif ($report_id == static::TEST_DAY_REPORT) {
            $data[] = [
                'Charts' => [
                    'Test Day Grouped By regions' => self::getTestDayMilkGroupedByRegions($country_id),
                ],
                'Boxes' => [
                    'Farmers With Animals With Test Day' => MilkingReport::getTestDayRecord($country_id, false),
                    'Animals With Test Day' => MilkingReport::getTestDayRecord($country_id, true),
                ],
            ];
        } elseif ($report_id == static::INSEMINATION_PD_CALVING_REPORT) {
            $data[] = [
                'Charts' => [
                    'Male Calves Grouped By Regions' => self::getCalvesByRegions(Animal::ANIMAL_TYPE_MALE_CALF, $country_id),
                    'Female Calves Grouped By Regions' => self::getCalvesByRegions(Animal::ANIMAL_TYPE_FEMALE_CALF, $country_id),
                ],
                'Boxes' => [
                    'Calving' => self::getEventCounts($country_id, AnimalEvent::EVENT_TYPE_CALVING),
                    'Insemination' => self::getEventCounts($country_id, AnimalEvent::EVENT_TYPE_AI),
                    'PD' => self::getEventCounts($country_id, AnimalEvent::EVENT_TYPE_PREGNANCY_DIAGNOSIS),
                    'Male Calves' => self::getEventCounts($country_id, Animal::ANIMAL_TYPE_MALE_CALF),
                    'Female Calves' => self::getEventCounts($country_id, Animal::ANIMAL_TYPE_FEMALE_CALF),
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
            list($newCondition, $newParams) = DbUtils::appendCondition('region_id', $id, $condition, $params);
            $count = Farm::find()->andWhere($newCondition, $newParams)
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

    public static function getFarmsGroupedByFarmType($country_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get farm types
        $farmTypes = Choices::getList(ChoiceTypes::CHOICE_TYPE_FARM_TYPE);
        //print_r($farmTypes);
        foreach ($farmTypes as $type => $label) {
            list($newCondition, $newParams) = DbUtils::appendCondition('farm_type', $type, $condition, $params);
            $count = Farm::find()->andWhere($newCondition, $newParams)
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
            list($newCondition, $newParams) = DbUtils::appendCondition('region_id', $id, $condition, $params);
            $count = Animal::find()->andWhere($newCondition, $newParams)
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

    /**
     * @param null $country_id
     * @param array $param
     * @return array
     * @throws \Exception
     */
    public static function getAnimalsGroupedByBreeds($country_id = null)
    {
        $condition = '';
        $params = [];
        //list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get breeds
        $breeds = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
        foreach ($breeds as $id => $label) {
            list($newCondition, $newParams) = DbUtils::appendCondition('main_breed', $id, $condition, $params);
            $count = Animal::find()->andWhere($newCondition, $newParams)
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
            list($newCondition, $newParams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Farm::find()->where($newCondition, $newParams)
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

    public static function getLSFAnimalsGroupedByBreeds($country_id = null, $param = [])
    {
        $condition = '';
        $params = [];
        //  list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get breeds
        $breeds = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS);
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
        list($condition, $params) = AnimalEvent::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newCondition, $newParams) = DbUtils::appendCondition('region_id', $id, $condition, $params);
            $count = AnimalEvent::find()->andWhere($newCondition, $newParams)
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

    public static function getDashboardCountryCategories($filter = []){
        $condition = [];
        $params = [];
        $data = [];
        if (Session::isPrivilegedAdmin() || Session::isCountryUser()) {
            if(Session::isCountryUser()){
                $condition['id'] = Session::getCountryId();
            }
            $countries = Country::getListData('id', 'name', false, $condition, $params);
            foreach ($countries as $id => $label) {
                $data[$id] = $label;
            }
        }
        elseif (Session::isOrganizationUser()){
            $condition['id'] = Session::getOrgId();
            $orgs = Organization::getListData('id', 'name', false, $condition, $params);
            foreach ($orgs as $id => $label) {
                $data[$id] = $label;
            }
        }

        if (isset($filter['country_id'])){
            $data = ArrayHelper::filter($data, [$filter['country_id']]);
        }
        if (isset($filter['org_id'])){
            $data = ArrayHelper::filter($data, [$filter['org_id']]);
        }

        return $data;
    }

    public static function getDashboardDateCategories($type = 'month', $max = 12, $format = 'Y-m-d', $from = null, $to = null){
        $to_date = $to !== null ? new \DateTime($to) :  new \DateTime('now');
        $to = $to_date->format('Y-m-d');
        $from_date = $from !== null ? new \DateTime($from) : $to_date->modify('-1 year')->format('Y-m-d');
        $from = $from_date->format('Y-m-d');
        $max_label = $max;
        $date_interval = DateUtils::getDateDiff($from, $to);
        $days_interval = $date_interval->days;
        $x_interval = (int)round(($days_interval / 30) / $max_label);
        #TODO: fix bug datespan skipping February
        return  DateUtils::generateDateSpan($from, $to, $x_interval, 'month', $format);
    }

    public static function rangeYears($from = null, $to = null){
        $curr_year = date("Y");
        $prev_year = date("Y", strtotime("-4 years"));
        $years = range($from ?? $prev_year, $to ?? $curr_year);
        return $years;
    }

    public static function rangeYearsDropdown(){
        $years = [];
        $range = static::rangeYears();
        rsort($range);
        foreach ($range as $year){
            $years[$year] = $year;
        }
        return $years;
    }

    public static function getQuarters($from = null, $to = null, $max = 12){
        $quarters = [];
        // 2020-04-23 to 2019-01-01
        $from_date = new \DateTime($from ?? 'now');
        $from_formatted = $from_date->format('Y-m-d');
        $to_date = ($to === null) ? $from_date->modify('-4 year') : new \DateTime($to);
        $to_formatted = $to_date->format('Y-m-d');

        $start_month = date( 'n', strtotime($to_formatted) );
        $start_year = date( 'Y', strtotime($to_formatted) );

        $end_month = date( 'n', strtotime($from_formatted) );
        $end_year = date( 'Y', strtotime($from_formatted) );

        $start_quarter = ceil($start_month/3);
        $end_quarter = ceil($end_month/3);

        $quarter = $start_quarter; // variable to track current quarter

        for( $y = $start_year; $y <= $end_year; $y++ ){
            if($y == $end_year)
                $max_qtr = $end_quarter;
            else
                $max_qtr = 4;

            for($q = $quarter; $q <= $max_qtr; $q++){

                $current_quarter = new \stdClass();

                $q_num = $q * 3;
                //$end_month_num = zero_pad($q * 3);
                $end_month_num = $q_num < 10 ? "0$q_num" : $q_num;

                //$start_month_num = zero_pad(($end_month_num - 2));
                $sm_num = ($end_month_num - 2);
                $start_month_num = $sm_num < 10 ? "0$sm_num" : $sm_num;

                // get month name from number

                //$q_start_month = month_name($start_month_num);
                $q_start_month = date('M', mktime(0, 0, 0, $start_month_num, 10));
                //$q_end_month = month_name($end_month_num);
                $q_end_month = date('M', mktime(0, 0, 0, $end_month_num, 10));

                $current_quarter->num = intval(round($q));
                $current_quarter->period = "Q$q ($q_start_month - $q_end_month) $y";
                $current_quarter->period_start = "$y-$start_month_num-01";      // yyyy-mm-dd

                // get get last date of given month (of year)
                $month_end_date = date("t", strtotime("$y-$end_month_num-1"));
                $current_quarter->period_end = "$y-$end_month_num-" . $month_end_date;

                $quarters[] = $current_quarter;
                unset($current_quarter);
            }

            $quarter = 1; // reset to 1 for next year
        }
        // if count($quarters) is less than or equal to $max, return the whole $quarters
        // otherwise return $n latest values of $quarters
        if(count($quarters) <= $max){
            return $quarters;
        }
        return array_slice($quarters, -$max, $max);
    }

    public static function getAnimalsCumulativeForDataViz($filter = []){
        // fetch animals with milk for each country per quarter, cumulatively
        $quarters = static::getQuarters(); // max 12 from today
        $countries = static::getDashboardCountryCategories($filter);
        foreach ($countries as $id => $label) {
            foreach ($quarters as $quarter){
                $params = [];
                $condition = [];
                if (!Session::isPrivilegedAdmin()){
                    list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
                }
                else {
                    list($condition, $params) = DbUtils::appendCondition('country_id', $id, $condition, $params);
                }
                $sum = Animal::find()->select('reg_date')
                    ->andWhere($condition, $params)
                    ->andWhere('DATE(reg_date) <= DATE(:end_date)', [':end_date' => $quarter->period_end])
                    ->count();
                $data[$label][] = [
                    'label' => $quarter->period . ' - ' . $quarter->period_end,
                    'value' => floatval(number_format($sum, 2, '.', '')),
                ];
            }
        };
        //dd($countries, $filter, $data);
        return $data;
    }

    public static function getAnimalsWithMilkForDataViz($filter = []){
        $quarters = static::getQuarters(); // max 12 from today
        $countries = static::getDashboardCountryCategories($filter);
        $all_results = [];
        $data = [];
        foreach ($quarters as $quarter){
            $params = [];
            $condition = '';
            list($condition, $params) = DbUtils::appendInCondition(Animal::tableName(). '.[[country_id]]', array_keys($countries), $condition, $params);
            #
            # how do we put this date condition as a subselect in the query and avoid the 12x loop?
            #
            if (!empty($condition))
                $condition .= ' AND ';
            $casted_date = DbUtils::castDATE(Animal::tableName().'.[[reg_date]]', Animal::getDb());
            $condition .= $casted_date . ' <= DATE(:end_date)';
            $params[':end_date'] = $quarter->period_end;

            $query = static::getAnimalsWithMilkQuery($condition, $params);
            $query->addSelect(Animal::tableName(). '.[[country_id]]');
            $query->groupBy([Animal::tableName(). '.[[country_id]]']);
            $results = $query->asArray()->all();
            $res = [];
            foreach ($results as $result){
                $res[$result['country_id']] = $result['count'];
            }
            $all_results[$quarter->period . ' - ' . $quarter->period_end] = $res;
        }
        foreach ($countries as $country_id => $country) {
            foreach ($all_results as $quarter_period => $result){
                $data[$country][] = [
                    'label' => $quarter_period,
                    'value' => floatval(number_format($result[$country_id] ?? 0, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    /**
     * @param string $condition
     * @param array $params
     * @return \yii\db\ActiveQuery
     *
     */
    public static function getAnimalsWithMilkQuery($condition = '', $params = []){
        $command = Animal::find()
            //->addSelect([Animal::tableName(). '.id', Animal::tableName(). '.entry_date'])
            //->addSelect(['Count('.AnimalEvent::tableName() . '.id) as milkrecords'])
            ->addSelect(['COUNT(DISTINCT('.Animal::tableName(). '.[[id]])) as count'])
            ->leftJoin(AnimalEvent::tableName(), AnimalEvent::tableName() . '.[[animal_id]] = ' . Animal::tableName() . '.[[id]]')
            ->andWhere(AnimalEvent::tableName() . '.[[event_type]] = '. AnimalEvent::EVENT_TYPE_MILKING)
            ->andWhere($condition, $params);

        return $command;
    }

    public static function getAnimalsWithMilk($condition = '', $params = [], $durationType = null){
        if (!empty($durationType)){

            $today = DateUtils::formatToLocalDate(date('Y-m-d H:i:s', time()), 'Y-m-d');
            $timezone = 'UTC';
            $this_month = DateUtils::formatDate($today, 'm', $timezone);
            $this_year = DateUtils::formatDate($today, 'Y', $timezone);
            $dateField = Animal::tableName().'.[[reg_date]]';

            switch ($durationType){
                case Animal::STATS_THIS_MONTH:
                    list($condition, $params) = DbUtils::appendCondition(DbUtils::castYEAR($dateField, Animal::getDb()), $this_year, $condition, $params);
                    list($condition, $params) = DbUtils::appendCondition(DbUtils::castMONTH($dateField, Animal::getDb()), $this_month, $condition, $params);
                    break;
            }
        }
        $command = static::getAnimalsWithMilkQuery($condition, $params);
        return $command->scalar();
    }

    public static function getMilkProductionForDataViz($filter = [])
    {
        $data = [];
        // get countries
        $countries = static::getDashboardCountryCategories($filter);
        $dates = static::getDashboardDateCategories();
        foreach ($countries as $id => $label) {
            foreach ($dates as $date){
                $params = [];
                $condition = [
                    'event_type' => AnimalEvent::EVENT_TYPE_MILKING,
                    //'country_id' => $id,
                ];
                if (!Session::isPrivilegedAdmin()){
                    list($condition, $params) = MilkingEvent::appendOrgSessionIdCondition($condition, $params);
                }
                else {
                    list($condition, $params) = DbUtils::appendCondition('country_id', $id, $condition, $params);
                }
                $totalMilkField = new Expression("JSON_UNQUOTE(JSON_EXTRACT(`core_animal_event`.`additional_attributes`, '$.\"62\"'))");
                $sum = MilkingEvent::find()->select($totalMilkField)->andWhere($condition, $params)->andWhere('YEAR(event_date) = YEAR(:date) AND MONTH(event_date) = MONTH(:date)', [':date' => $date])->sum($totalMilkField);
                $data[$label][] = [
                    'label' => $date,
                    'value' => floatval(number_format($sum, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getCalfWeightGrowthForDataViz(){
        $data = [];
        // get countries
        $countries = static::getDashboardCountryCategories();
        $dates = static::getDashboardDateCategories();
        foreach ($countries as $id => $label) {
            foreach ($dates as $date){
                $params = [];
                $condition = [
                    'event_type' => AnimalEvent::EVENT_TYPE_WEIGHTS,
                    //'country_id' => $id,
                ];
                if (!Session::isPrivilegedAdmin()){
                    list($condition, $params) = WeightEvent::appendOrgSessionIdCondition($condition, $params);
                }
                else {
                    list($condition, $params) = DbUtils::appendCondition('country_id', $id, $condition, $params);
                }
                $weightField = new Expression("JSON_UNQUOTE(JSON_EXTRACT(`core_animal_event`.`additional_attributes`, '$.\"136\"'))");
                $sum = WeightEvent::find()->select($weightField)->andWhere($condition, $params)->andWhere('YEAR(event_date) = YEAR(:date) AND MONTH(event_date) = MONTH(:date)', [':date' => $date])->average($weightField);
                $data[$label][] = [
                    'label' => $date,
                    'value' => floatval(number_format($sum, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getAnimalsByCategoriesForDataViz($filter = []){
        $data = [];
        $countries = static::getDashboardCountryCategories();
        $animal_types = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, false);
        $years = static::rangeYears();
        foreach ($countries as $id => $country) {
            foreach ($animal_types as $typeid => $type){
                foreach ($years as $year){
                    $params = [];
                    $condition = '';
                    list($condition, $params) = DbUtils::appendCondition('country_id', $id, $condition, $params);
                    list($condition, $params) = DbUtils::appendCondition('animal_type', $typeid, $condition, $params);
                    //list($condition, $params) = DbUtils::appendCondition(DbUtils::castYEAR('reg_date', Animal::getDb()), $year, $condition, $params, 'AND', '<=');
                    if (!empty($condition))
                        $condition .= ' AND ';
                    $casted_date = DbUtils::castYEAR(Animal::tableName().'.[[reg_date]]', Animal::getDb());
                    $condition .= $casted_date . ' <= :end_date';
                    $params[':end_date'] = $year;
                    $count = Animal::getCount($condition, $params);
                    $data[$country][$type][$year] = [
                        'label' => $year,
                        'value' => floatval(number_format($count, 2, '.', '')),
                    ];
                }

            }
        };
        return $data;
    }

    public static function getRegisteredAnimalsForDataViz(){
        $data = [];
        $countries = static::getDashboardCountryCategories();
        $animal_types = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_TYPES, false);
        foreach ($countries as $id => $country) {
            foreach ($animal_types as $typeid => $type){
                $params = [];
                $condition = [
                    'animal_type' => $typeid,
                    //'country_id' => $id,
                ];
                if (!Session::isPrivilegedAdmin()){
                    list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
                }
                else {
                    list($condition, $params) = DbUtils::appendCondition('country_id', $id, $condition, $params);
                }
                $count = Animal::getCount($condition, $params);
                $data[$country][] = [
                    'label' => $type,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getAnimalsByBreedsForDataViz(){
        $data = [];
        $countries = static::getDashboardCountryCategories();
        $animal_breeds = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, false);
        foreach ($countries as $id => $country) {
            $data[$country] = static::getAnimalsGroupedByBreeds($id);
        };
        return $data;
    }

    public static function getAIForDataViz(){
        $data = [];
        $countries = static::getDashboardCountryCategories();
        $animal_breeds = Choices::getList(ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS, false);
        foreach ($countries as $id => $country) {
            foreach ($animal_breeds as $breedid => $type){
                $params = [];
                $condition = [
                    'event_type' => AnimalEvent::EVENT_TYPE_AI,
                    //'country_id' => $id,
                ];
                if (!Session::isPrivilegedAdmin()){
                    list($condition, $params) = AnimalEvent::appendOrgSessionIdCondition($condition, $params);
                }
                else {
                    list($condition, $params) = DbUtils::appendCondition('country_id', $id, $condition, $params);
                }
                $breedField = new Expression("JSON_UNQUOTE(JSON_EXTRACT(`core_animal_event`.`additional_attributes`, '$.\"111\"'))");
                $count = AnimalEvent::find()->select('id')->andWhere($condition, $params)->andWhere($breedField . ' = :breedId', ['breedId' => $breedid])->count();
                $data[$country][] = [
                    'label' => $type,
                    'value' => floatval(number_format($count, 2, '.', '')),
                ];
            }
        };
        return $data;
    }

    public static function getCountryAvgBodyWeight($country_id, $region_id = null, $year = '2020'){
        $subquery = new Expression('(
                      select
                        `core_animal_event`.`id` AS `event_id`,
                        `animal`.`region_id`,
                        `core_animal_event`.`event_date` AS `milk_date`,
                        year(`core_animal_event`.`event_date`) AS `year`,
                        quarter(`core_animal_event`.`event_date`) AS `quarter`,
                        `core_animal_event`.`country_id` AS `country_id`,
                        json_unquote(json_extract(`core_animal_event`.`additional_attributes`,
                        \'$.\"220\"\')) AS `body_weight`,
                        json_unquote(json_extract(`core_animal_event`.`additional_attributes`,
                        \'$.\"219\"\')) AS `heart_girth`,
                        json_unquote(json_extract(`core_animal_event`.`additional_attributes`,
                        \'$.\"59\"\')) AS `milk_yield_morning`,
                        json_unquote(json_extract(`core_animal_event`.`additional_attributes`,
                        \'$.\"68\"\')) AS `milk_yield_noon`,
                        json_unquote(json_extract(`core_animal_event`.`additional_attributes`,
                        \'$.\"61\"\')) AS `milk_yield_evening`,
                        json_unquote(json_extract(`core_animal_event`.`additional_attributes`,
                        \'$.\"62\"\')) AS `milk_yield_total`
                    from
                        ((`core_animal_event`
                    left join `core_animal` `animal` on ((`core_animal_event`.`animal_id` = `animal`.`id`))
                        )
                    join `core_country` `country` on
                        (((`country`.`id` = `core_animal_event`.`country_id`)
                        and (`core_animal_event`.`event_type` = :event_type)
                        ))
                        )
                        )'
        );
        $select = new Expression('
                        `a`.`country_id`,
                        `a`.`quarter`,
                        `a`.`year`,
                        CONCAT(`year`, "-Q", `quarter`) AS `year_quarter`,
                        round(avg(`a`.`milk_yield_total`), 4) AS `avg_milk_yield_total`,
                        round(avg(`a`.`body_weight`), 4) AS `avg_body_weight`,
                        avg((case 
                                when (`a`.`body_weight` = 0) then 0 
                                when (`a`.`body_weight` is null) then 0 
                                when (`a`.`body_weight` = \'null\') then 0 
                                else round((`a`.`milk_yield_total` / `a`.`body_weight`), 4) end)
                            ) AS `milk_per_weight`'
        );

        $query = new Query();
        $query->from(['a' => $subquery]);
        $query->addParams([
            ':event_type' => AnimalEvent::EVENT_TYPE_MILKING,
        ]);
        $query->addSelect($select);
        $query->andWhere('`year` = :year AND `country_id` = :country_id ', [':year' => $year, ':country_id' => $country_id]);
        $query->addGroupBy("year, quarter, country_id");
        # if we have filtered region_id, add group by region_id
        if ($region_id !== null && $region_id != ''){
            $query->andWhere('`region_id` = :region_id ', [':region_id' => $region_id]);
            $query->addGroupBy("region_id");
        }
        $query->orderBy('quarter ASC');

        $command = $query->createCommand();
        return $command->queryAll();
    }

    public static function getCountryFertility($country_id, $region_id = null){
        $select = new Expression('
            `country_id`, 
            #`region_id`, 
            `year`, 
            AVG(`fertility`) as `avg_fertility`
        ');

        $positive_pd_all = new Expression('(
            select
                `core_animal_event`.`id` AS `pdEventID`,
                `core_animal_event`.`event_date` AS `examinationDate`,
                year(`core_animal_event`.`event_date`) AS `year`,
                quarter(`core_animal_event`.`event_date`) AS `quarter`,
                `animal`.`tag_id` AS `animalTagID`,
                `animal`.`id` AS `animal_id`,
                `country`.`name` AS `country`,
                `animal`.`region_id`
            from
                ((`core_animal_event`
            left join `core_animal` `animal` on
                ((`core_animal_event`.`animal_id` = `animal`.`id`)))
            join `core_country` `country` on
                (((`country`.`id` = `core_animal_event`.`country_id`)
                and (`core_animal_event`.`event_type` = 4)
                and (json_unquote(json_extract(`core_animal_event`.`additional_attributes`,
                \'$."131"\')) = 1))))
        )');

        $positive_pd = new Expression('(
            select
                `v_rpt_positive_pd_all`.`pdEventID` AS `pdEventID`,
                `v_rpt_positive_pd_all`.`examinationDate` AS `examinationDate`,
                `v_rpt_positive_pd_all`.`year` AS `year`,
                `v_rpt_positive_pd_all`.`quarter` AS `quarter`,
                `v_rpt_positive_pd_all`.`animalTagID` AS `animalTagID`,
                `v_rpt_positive_pd_all`.`animal_id` AS `animal_id`,
                `v_rpt_positive_pd_all`.`country` AS `country`
            from
                (' . $positive_pd_all->expression . ') `v_rpt_positive_pd_all`
        )');

        $insemination = new Expression('(
            select
				    `core_animal_event`.`event_date` AS `aIDate`,
				    year(`core_animal_event`.`event_date`) AS `YEAR`,
				    monthname(`core_animal_event`.`event_date`) AS `MONTH`,
				    `animal`.`id` AS `Animal_ID`,
				    `animal`.`tag_id` AS `animalTagID`,
				    `animal`.`country_id`,
				    `animal`.`region_id`
				from
				    ((`core_animal_event`
				join `core_animal` `animal` on
				    ((`core_animal_event`.`animal_id` = `animal`.`id`)))
				join (' . $positive_pd->expression . ') `v_rpt_positive_pd` on
				    (((`v_rpt_positive_pd`.`animal_id` = `core_animal_event`.`animal_id`)
				    and (`core_animal_event`.`event_type` = :event_type))))
        )');

        $from = new Expression('(
            select
                #`a`.`animal_id` AS `animal_id`,
                `a`.`country_id` AS `country_id`,
                `a`.`region_id` AS `region_id`,
                `b`.`year` AS `year`,
                #`b`.`quarter` AS `quarter`,
                #`b`.`pregnancies` AS `pregnancies`,
                #`a`.`inseminations` AS `inseminations`,
                (case
                    when (`a`.`inseminations` = 0) then 0
                    when (`a`.`inseminations` is null) then 0
                    when (`a`.`inseminations` = \'null\') then 0
                    else round(((`b`.`pregnancies` / `a`.`inseminations`) * 100), 2) end) AS `fertility`
            from (
                 (
                    select
                        `v_rpt_insemenation`.`Animal_ID` AS `animal_id`,
                        `v_rpt_insemenation`.`country_id` AS `country_id`,
                        `v_rpt_insemenation`.`region_id` AS `region_id`,
                        count(`v_rpt_insemenation`.`Animal_ID`) AS `inseminations`
                    from (
                        '.$insemination->expression.'
                    ) `v_rpt_insemenation`
	                group by
	                    `v_rpt_insemenation`.`Animal_ID`
                 ) `a`
                 left join (
                     select
                        `v_rpt_positive_pd_all`.`animal_id` AS `animal_id`,
                        `v_rpt_positive_pd_all`.`year` AS `year`,
                        `v_rpt_positive_pd_all`.`quarter` AS `quarter`,
                        count(`v_rpt_positive_pd_all`.`animal_id`) AS `pregnancies`
                     from (
                        '.$positive_pd_all->expression.'
                     ) `v_rpt_positive_pd_all`
                     group by
                        `v_rpt_positive_pd_all`.`animal_id`,
                        `v_rpt_positive_pd_all`.`year`,
                        `v_rpt_positive_pd_all`.`quarter`
                 ) `b` on ((`a`.`animal_id` = `b`.`animal_id`))
            )
            group by 
                `country_id`, 
                `region_id`, 
                `year`,
                #`quarter`, 
                `pregnancies`, 
                `inseminations`
	     )
        ');

        $query = new Query();
        $query->from(['f' => $from]);
        $query->addSelect($select);
        $query->addParams([
            ':event_type' => AnimalEvent::EVENT_TYPE_AI,
        ]);
        $query->andWhere('`country_id` = :country_id ', [':country_id' => $country_id]);
        $query->addGroupBy("year, country_id");
        if ($region_id !== null && $region_id != ''){
            $query->andWhere('`region_id` = :region_id ', [':region_id' => $region_id]);
            $query->addGroupBy("region_id");
        }
        $query->orderBy('year ASC');

        $command = $query->createCommand();
        return $command->queryAll();
    }

    public static function getCalvesByRegions($animal_type, $country_id = null)
    {
        $condition = '';
        $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params);
        $data = [];
        // get regions
        $regions = CountryUnits::getListData('id', 'name', '', ['level' => CountryUnits::LEVEL_REGION]);
        foreach ($regions as $id => $label) {
            list($newCondition, $newParams) = DbUtils::appendCondition('region_id', $id, $condition, $params);

            $count = Animal::find()->andWhere($newCondition, $newParams)
                ->andFilterWhere(['animal_type' => $animal_type])
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


    public static function getFarmCounts($country_id = null, $household = false, $params = null, $farm_type = null)
    {
        $query = Farm::find();
        $query->andFilterWhere(['country_id' => $country_id]);
        $query->andFilterWhere(['farm_type' => $farm_type]);

        if ($household){
            $query->andWhere(['JSON_UNQUOTE(JSON_EXTRACT(`core_farm`.`additional_attributes`, \'$."36"\'))' => $params]);
        }
        if (Session::isFieldAgent()) {
            $query->andWhere(['field_agent_id' => Session::getUserId()]);
        }
        $condition = ''; $params = [];
        list($condition, $params) = Farm::appendOrgSessionIdCondition($condition, $params);
        $query->andWhere($condition, $params);
        return $query->count();
    }

    public static function getAnimalCounts($country_id = null, $animal_type = null)
    {
        $query = Animal::find();
        $query->andFilterWhere([Animal::tableName().'.country_id' => $country_id]);
        $query->andFilterWhere([Animal::tableName().'.animal_type' => $animal_type]);

        if (Session::isFieldAgent()) {
            $query->joinWith('farm');
            $query->andWhere([Farm::tableName().'.field_agent_id' => Session::getUserId()]);
        }

        $condition = []; $params = [];
        list($condition, $params) = Animal::appendOrgSessionIdCondition($condition, $params, false, Animal::tableName());
        $query->andWhere($condition, $params);

        return $query->count();
    }

    public static function getEventCounts($country_id = null, $event_type = null)
    {
        $query = AnimalEvent::find();
        $query->andFilterWhere(['country_id' => $country_id]);
        $query->andFilterWhere(['event_type' => $event_type]);

        if (Session::isFieldAgent()) {
            $query->andWhere(['field_agent_id' => Session::getUserId()]);
        }
        $condition = ''; $params = [];
        list($condition, $params) = AnimalEvent::appendOrgSessionIdCondition($condition, $params);
        $query->andWhere($condition, $params);

        return $query->count();
    }
}