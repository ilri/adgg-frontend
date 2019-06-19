<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 9:53 PM
 */

namespace backend\modules\core\models;


interface UploadExcelInterface
{
    /**
     * @return array
     */
    public function getExcelColumns();
}