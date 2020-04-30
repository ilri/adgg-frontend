<?php

namespace console\dataMigration\mistro\stanley2;


class Cowtests extends \console\dataMigration\mistro\klba\Cowtests
{
    use MigrationTrait;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestDay()
    {
        return $this->hasOne(Testdays::class, ['TestDays_ID' => 'CowTests_TDayID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLactation()
    {
        return $this->hasOne(Lacts::class, ['Lacts_ID' => 'CowTests_LactID']);
    }

    public static function getCowMigrationIdPrefix()
    {
        return Cows::getMigrationIdPrefix();
    }

    public static function getTestDayMigrationIdPrefix()
    {
        return Testdays::getMigrationIdPrefix();
    }

    public static function getLactMigrationIdPrefix()
    {
        return Lacts::getMigrationIdPrefix();
    }

    public static function getTestDaysData($testDayIds)
    {
        return Testdays::getData(['TestDays_ID', 'TestDays_Date', 'TestDays_TestType'], ['TestDays_ID' => $testDayIds]);
    }
}