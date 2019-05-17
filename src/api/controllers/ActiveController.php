<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/02/03
 * Time: 1:57 PM
 */

namespace api\controllers;

class ActiveController extends \yii\rest\ActiveController
{
    public $serializer = [
        'class' => \yii\rest\Serializer::class,
    ];

    /**
     * Define this in each controller to enable ACL
     * @var string
     */
    protected $resource;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => \api\actions\Index::class,
                'modelClass' => $this->modelClass,
            ],
            'view' => [
                'class' => \api\actions\View::class,
                'modelClass' => $this->modelClass,
            ],
            'create' => [
                'class' => \api\actions\Create::class,
                'modelClass' => $this->modelClass,
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => \api\actions\Update::class,
                'modelClass' => $this->modelClass,
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => \api\actions\Delete::class,
                'modelClass' => $this->modelClass,
            ],
            'options' => [
                'class' => \yii\rest\OptionsAction::class,
            ],
        ];
    }
}