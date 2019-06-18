<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-18
 * Time: 11:48 PM
 */

namespace backend\modules\conf\controllers;


use backend\modules\conf\settings\CountryAdministrativeUnits;
use yii2mod\settings\actions\SettingsAction;

class CountryUnitsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Country Unit';
    }

    function actions()
    {
        return [
            'index' => [
                'class' => SettingsAction::class,
                'modelClass' => CountryAdministrativeUnits::class,
                'sectionName' => CountryAdministrativeUnits::SECTION_ADMIN_UNITS,
                'view' => 'index',
            ],
        ];
    }
}