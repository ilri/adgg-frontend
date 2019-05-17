<?php

namespace backend\modules\help\models;

use backend\modules\help\Help;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "help_content".
 *
 * @property integer $id
 * @property integer $module_id
 * @property string $name
 * @property string $slug
 * @property string $content
 * @property string $permissions
 * @property string $tags
 * @property string $secondary_permissions
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $is_active
 *
 * @property HelpModules $module
 */
class HelpContent extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    public $enableAuditTrail = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%help_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'name', 'content'], 'required'],
            [['module_id', 'is_active',], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 128],
            ['name', 'unique'],
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
            'module_id' => 'Module',
            'name' => 'Help Topic',
            'slug' => 'Slug',
            'content' => 'Content',
            'permissions' => 'Permissions',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'is_active' => 'Active',
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchParams()
    {
        return [
            'id',
            'name',
            'module_id'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(HelpModules::class, ['id' => 'module_id']);
    }

    /**
     * Get permissions from a key based array
     * @param $values
     * @return mixed
     */
    public static function getPermissions($values)
    {
        // if the values are an array, like [1, 2, 4], we loop over
        // the array, and get the values by key from the help array
        if (is_array($values)) {
            $names = '';
            foreach ($values as $value) {
                $names .= Help::$permissions[$value] . ', ';
            }
            // remove last comas, and last whitespace if any
            return rtrim(str_replace_last(',', '', $names));
        }
        // if the values were a plain string, we attempt to cast to an integer and
        // then fetch by key
        if (is_string($values)) {
            return Help::$permissions[(int)$values];
        }
        return $values;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->slug = str_slug($this->name);
        // these dummy tags will allow us to find partial search results
        $this->tags = self::getPermissions($this->permissions);
        // just fill all permissions by default
        $this->permissions = json_encode(array_keys(Help::$permissions));
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->permissions = json_decode($this->permissions);
        parent::afterFind();
    }

}
