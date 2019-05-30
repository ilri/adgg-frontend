<?php
/**
 * Created by PhpStorm.
 * @author Fred <mconyango@gmail.com>
 * Date: 2018-05-24
 * Time: 20:02
 */

namespace backend\modules\core\controllers;


use backend\modules\conf\Constants;

class MasterDataController extends \backend\modules\conf\controllers\Controller
{
    public function init()
    {
        parent::init();
        $this->resource = Constants::RES_SETTINGS;
        $this->activeSubMenu=Constants::SUBMENU_MASTER_DATA;
    }
}