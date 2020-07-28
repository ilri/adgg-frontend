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
}