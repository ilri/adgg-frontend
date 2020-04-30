<?php

namespace console\dataMigration\mistro\stanley2;

class Testdays extends \console\dataMigration\mistro\klba\Testdays
{
    use MigrationTrait;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkRecords()
    {
        return $this->hasMany(Cowtests::class, ['CowTests_TDayID' => 'TestDays_ID']);
    }

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'MILKING_EVENT_';
    }

}