<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 6:43 PM
 */

namespace backend\modules\core\controllers;


use backend\modules\core\Constants;

class ClientController extends Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_CLIENT;
        $this->resourceLabel = 'Client';
    }

    public function actionIndex($org_id, $from = null, $to = null)
    {

    }

}