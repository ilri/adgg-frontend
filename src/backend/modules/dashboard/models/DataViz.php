<?php

namespace backend\modules\dashboard\models;

use common\helpers\Lang;
use common\helpers\Utils;

class DataViz
{
    const GRAPH_PIE = 'pie';
    const GRAPH_LINE = 'line';
    const GRAPH_SPLINE = 'spline';
    const GRAPH_COLUMN = 'column';
    const GRAPH_BAR = 'bar';
    const GRAPH_AREA = 'area';
    const GRAPH_AREASPLINE = 'areaspline';

    const ANIMAL_TYPE_COW = 1;
    const ANIMAL_TYPE_CALF = 2;


    /**
     * get all the graph types
     * @param bool $add_tip
     * @param array $except
     * @return array
     */
    public static function graphTypeOptions($add_tip = false, $except = [])
    {
        $options = [
            self::GRAPH_BAR => Lang::t('Bar'),
            self::GRAPH_COLUMN => Lang::t('Column'),
            self::GRAPH_LINE => Lang::t('Line'),
            self::GRAPH_SPLINE => Lang::t('Smooth Line'),
            self::GRAPH_PIE => Lang::t('Pie'),
            self::GRAPH_AREA => Lang::t('Area'),
            self::GRAPH_AREASPLINE => Lang::t('Smooth Area'),
        ];

        if (!empty($except) && is_array($except)) {
            foreach ($except as $e) {
                if (isset($options[$e])) {
                    unset($options[$e]);
                }
            }
        }

        return Utils::appendDropDownListPrompt($options, $add_tip);
    }

    public static function animalTypeOptions($add_tip = false)
    {
        $options = [
            self::ANIMAL_TYPE_CALF => Lang::t('Calf'),
            self::ANIMAL_TYPE_COW => Lang::t('Cow'),
        ];
        return Utils::appendDropDownListPrompt($options, $add_tip);
    }

    public static function ageRangeCalves($add_tip = false){
        $options = [
            '0-6' => Lang::t('0-6 Months'),
            '6-12' => Lang::t('6-12 Months'),
            '12-18' => Lang::t('12-18 Months'),
            '18-24' => Lang::t('18-24 Months'),
        ];
        return Utils::appendDropDownListPrompt($options, $add_tip);
    }

    public static function ageRangeCows($add_tip = false){
        $options = [
            '2-3' => Lang::t('2-3 years'),
            '3-4' => Lang::t('3-4 years'),
            '4-5' => Lang::t('4-5 years'),
            '5-6' => Lang::t('5-6 years'),
            '6-7' => Lang::t('6-7 years'),
            '8>' => Lang::t('8 and above'),
        ];
        return Utils::appendDropDownListPrompt($options, $add_tip);
    }

    public static function dimRange($add_tip = false){
        $options = [
            '0-100' => Lang::t('0-100 Days'),
            '100-200' => Lang::t('100-200 Days'),
            '200-300' => Lang::t('200-300 Days'),
            '300>' => Lang::t('300 and Above'),
        ];
        return Utils::appendDropDownListPrompt($options, $add_tip);
    }
}