<?php

namespace backend\modules\help\models;

use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "help_modules".
 *
 * @property integer $id
 * @property string $resource_name
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $is_active
 * @property integer $is_for_android
 *
 * @property HelpContent[] $helpContents
 */
class HelpModules extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    public $enableAuditTrail = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%help_modules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active', 'is_for_android'], 'integer'],
            [['resource_name'], 'string', 'max' => 50],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug', 'name'], 'unique'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resource_name' => 'Resource Name',
            'name' => 'Name',
            'slug' => 'Slug',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'is_active' => 'Active',
            'is_for_android' => 'Is For Android',
        ];
    }

    /**
     * Search params for the active search
     * ```php
     *   return [
     *       ["name","_searchField","AND|OR"],//default is AND only include this param if there is a need for OR condition
     *       'id',
     *       'email'
     *   ];
     * ```
     * @return array
     */
    public function searchParams()
    {
        return [
            'name', 'resource_name',
            'is_for_android'
        ];
    }


    public function beforeSave($insert)
    {
        $this->slug = str_slug($this->name);
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHelpContents()
    {
        return $this->hasMany(HelpContent::class, ['module_id' => 'id']);
    }
}
