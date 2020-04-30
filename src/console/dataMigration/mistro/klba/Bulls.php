<?php

namespace console\dataMigration\mistro\klba;

use backend\modules\core\models\Animal;
use console\dataMigration\mistro\Helper;
use console\dataMigration\mistro\MigrationBase;
use console\dataMigration\mistro\MigrationInterface;

/**
 * This is the model class for table "bulls".
 *
 * @property string $Bulls_ID
 * @property int $Bulls_species
 * @property string $Bulls_Nasis2
 * @property string $Bulls_Nasis1
 * @property string $Bulls_RegName
 * @property string|null $Bulls_RegNasis
 * @property string|null $Bulls_ShortName
 * @property string|null $Bulls_ActiveNasis
 * @property float|null $Bulls_Herd
 * @property string|null $Bulls_Common2
 * @property string|null $Bulls_EarTag
 * @property string|null $Bulls_Birth
 * @property string|null $Bulls_Sire
 * @property string|null $Bulls_SireF
 * @property string|null $Bulls_Dam
 * @property string|null $Bulls_BreedS
 * @property string|null $Bulls_Registered
 * @property string|null $Bulls_HerdBookCty
 * @property string|null $Bulls_HerdBook
 * @property int|null $Bulls_Origin
 * @property int|null $Bulls_Grade
 * @property string|null $Bulls_TermDate
 * @property int|null $Bulls_TermCode
 * @property string|null $Bulls_NLISID
 * @property string|null $Bulls_MGSire
 * @property string|null $Bulls_Defects
 * @property string|null $Bulls_PTBull
 * @property string|null $Bulls_SemenAvail
 * @property string|null $Bulls_InternatID
 * @property string|null $Bulls_SexedAvail
 * @property int|null $Bulls_DPCentre
 * @property string|null $Bulls_OwnerCode
 * @property int $Bulls_Upload
 * @property int $Bulls_Download
 * @property string|null $Bulls_Modified
 * @property string|null $Bulls_ModifiedBy
 * @property int $Bulls_HideFlag
 * @property int $Bulls_Locked
 */
class Bulls extends MigrationBase implements MigrationInterface
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bulls}}';
    }

    public static function migrateData()
    {
        $condition = ['Bulls_HideFlag' => 0, 'Bulls_species' => 0];
        $query = static::find()->andWhere($condition);
        $totalRecords = static::getCount($condition);
        /* @var $dataModels $this[] */
        $n = 1;
        $countryId = Helper::getCountryId(\console\dataMigration\mistro\Constants::KENYA_COUNTRY_CODE);
        $orgId = Helper::getOrgId(static::getOrgName());
        $model = new Animal(['country_id' => $countryId, 'org_id' => $orgId, 'scenario' => Animal::SCENARIO_MISTRO_DB_BULL_UPLOAD]);
        foreach ($query->batch(1000) as $i => $dataModels) {
            foreach ($dataModels as $dataModel) {
                $herdModel = Cows::getHerd($dataModel->Bulls_Herd);
                $newModel = clone $model;
                if (null !== $herdModel) {
                    $newModel->herd_id = $herdModel->id;
                    $newModel->farm_id = $herdModel->farm_id;
                }
                $newModel->migration_id = Helper::getMigrationId($dataModel->Bulls_ID, static::getMigrationIdPrefix());
                $newModel->tag_id = $dataModel->Bulls_Nasis1;
                $newModel->name = $dataModel->Bulls_RegName;
                $newModel->animal_type = 5;
                $newModel->short_name = $dataModel->Bulls_ShortName;
                $newModel->animal_eartag_id = $dataModel->Bulls_EarTag;
                $newModel->sex = 1;
                $newModel->birthdate = $dataModel->Bulls_Birth;
                if ($newModel->birthdate == '0000-00-00') {
                    $newModel->birthdate = null;
                }
                $newModel->sire_tag_id = Helper::getMigrationId($dataModel->Bulls_Sire, static::getMigrationIdPrefix());
                $newModel->dam_tag_id = Helper::getMigrationId($dataModel->Bulls_Dam, static::getCowMigrationIdPrefix());
                $newModel->breed_composition_details = $dataModel->Bulls_BreedS;
                $newModel->country_of_origin = $dataModel->Bulls_HerdBookCty;
                $newModel->herd_book_no = $dataModel->Bulls_HerdBook;
                $newModel->animal_grade = $dataModel->Bulls_Grade;
                $newModel->animal_exit_date = $dataModel->Bulls_TermDate;
                $newModel->animal_exit_code = $dataModel->Bulls_TermCode;
                $newModel->reg_date = $dataModel->Bulls_Modified;
                if ($newModel->reg_date == '0000-00-00') {
                    $newModel->reg_date = null;
                }

                static::saveModel($newModel, $n, $totalRecords);
                $n++;
            }
        }
    }

    public static function getMigrationIdPrefix()
    {
        return Migrate::DATA_SOURCE_PREFIX . 'BULLS_';
    }

    public static function getCowMigrationIdPrefix()
    {
        return Cows::getMigrationIdPrefix();
    }

}
