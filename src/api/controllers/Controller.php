<?php
/**
 * Created by PhpStorm.
 * @author: Fred <fred@btimillman.com>
 * Date & Time: 2017-05-02 7:14 PM
 */

namespace api\controllers;


class Controller extends \yii\rest\Controller
{
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => \yii\rest\OptionsAction::class,
        ];
        return $actions;
    }
}