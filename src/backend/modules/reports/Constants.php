<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/05/16
 * Time: 6:19 PM
 */

namespace backend\modules\reports;


use common\helpers\Utils;

class Constants
{
    //resource
    const RES_REPORTS = 'REPORTS';
    const RES_REPORTS_SETTINGS = 'REPORTS_SETTINGS';
    //menu
    const MENU_REPORTS = 'REPORTS';

    // REPORT TYPES
    const REPORT_TYPE_MILKDATA = 1;
    const REPORT_TYPE_PEDIGREE = 2;

    //ANIMAL GRAPH GROUP BY
    const ANIMAL_GRAPH_GROUP_BY_ANIMAL_TYPES = 1;
    const ANIMAL_GRAPH_GROUP_BY_BREEDS = 2;

    /**
     * @param int $intVal
     * @return string
     */
    public static function decodeAnimalGraphGroupBy($intVal)
    {
        switch ($intVal) {
            case self::ANIMAL_GRAPH_GROUP_BY_ANIMAL_TYPES:
                return 'Group by Animal Types';
            case self::ANIMAL_GRAPH_GROUP_BY_BREEDS:
                return 'Group by Animal Breeds';
        }
    }

    /**
     * @param mixed $prompt
     * @return array
     */
    public static function animalGraphGroupByOptions($prompt = false)
    {
        $values = [
            self::ANIMAL_GRAPH_GROUP_BY_ANIMAL_TYPES => static::decodeAnimalGraphGroupBy(self::ANIMAL_GRAPH_GROUP_BY_ANIMAL_TYPES),
            self::ANIMAL_GRAPH_GROUP_BY_BREEDS => static::decodeAnimalGraphGroupBy(self::ANIMAL_GRAPH_GROUP_BY_BREEDS),
        ];
        return Utils::appendDropDownListPrompt($values, $prompt);
    }
}