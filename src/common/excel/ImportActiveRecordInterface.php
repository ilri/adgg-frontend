<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-06-19
 * Time: 9:53 PM
 */

namespace common\excel;


interface ImportActiveRecordInterface
{
    /**
     * @return array
     */
    public function getExcelColumns();
}