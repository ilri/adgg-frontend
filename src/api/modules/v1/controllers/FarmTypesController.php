<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use yii\web\ForbiddenHttpException;

class FarmTypesController extends ActiveController
{
    use JwtAuthTrait;

    public $modelClass = Choices::class;

    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        $data = [];
        $types = Choices::getData('value, label', ['list_type_id' => ChoiceTypes::CHOICE_TYPE_FARM_TYPE]);

        foreach ($types as $type) {
            $data[] = [
                'id' => $type['value'],
                'label' => $type['label'],
            ];
        }
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $data;
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");

        }
    }
}