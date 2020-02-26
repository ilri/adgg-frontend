<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-18
 * Time: 11:49 AM
 */

namespace backend\modules\dashboard\controllers;


use yii\db\Connection;

class TestController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        /* @var $mistroKeDb Connection */
        $mistroKeDb = \Yii::$app->mistroKeDb;

        $sql = "SELECT * FROM {{%bulls}} WHERE 1 LIMIT 100;";
        $conn = $mistroKeDb->createCommand($sql);
        $data = $conn->queryAll();
        dd($data);
    }
}