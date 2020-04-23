<?php

namespace api\modules\v1\controllers;


use api\controllers\ActiveController;
use api\controllers\JwtAuthTrait;
use backend\modules\auth\Session;
use backend\modules\core\models\Choices;
use backend\modules\core\models\ChoiceTypes;
use yii\web\ForbiddenHttpException;

class BreedsController extends ActiveController
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
        $breeds = Choices::getData('value, label', ['list_type_id' => ChoiceTypes::CHOICE_TYPE_ANIMAL_BREEDS]);

        foreach ($breeds as $breed) {
            $data[] = [
                'id' => $breed['value'],
                'label' => $breed['label'],
            ];
        }
        if (Session::isPrivilegedAdmin() || Session::isCountryUser() || Session::isOrganizationUser()) {
            return $data;
        } else {
            throw new ForbiddenHttpException("Not allowed to access this page");
        }
    }
}