<?php

namespace backend\modules\auth\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use Yii;

/**
 * This is the model class for table "auth_roles".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $readonly
 * @property integer $level_id
 * @property int $is_field_agent
 * @property string $created_at
 * @property integer $created_by
 * @property int $is_active
 *
 * @property UserLevels $level
 */
class Roles extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_roles}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(UserLevels::class, ['id' => 'level_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['readonly', 'level_id', 'is_field_agent'], 'integer'],
            [['is_active'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique', 'message' => Lang::t('{attribute} {value} already exists.')],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Lang::t('Name'),
            'description' => Lang::t('Description'),
            'level_id' => Lang::t('Account Type'),
            'is_active' => Lang::t('Active'),
            'is_field_agent' => Lang::t('Is Field Agent'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function searchParams()
    {
        return [
            ['name', 'name'],
            'level_id',
            'is_active',
            'is_field_agent'
        ];
    }

    /**
     * Get users in a role
     * @param integer $role_id
     * @return array $user_ids
     * @throws \Exception
     */
    public static function getUsers($role_id)
    {
        return Users::getColumnData('id', ['role_id' => $role_id]);
    }

    /**
     * Update role users
     * @param string $role_id
     * @param array $user_ids
     * @throws \yii\db\Exception
     * @deprecated
     */
    public static function updateRoleUsers($role_id, $user_ids = [])
    {
        Yii::$app->db->createCommand()
            ->update(Users::tableName(), ['role_id' => null], ['role_id' => $role_id])
            ->execute();

        if (!empty($user_ids)) {
            foreach ($user_ids as $user_id) {
                Yii::$app->db->createCommand()
                    ->update(Users::tableName(), ['role_id' => $role_id], ['id' => $user_id])
                    ->execute();
            }
        }
    }

    /**
     * @return bool
     */
    public function isFieldAgent()
    {
        return $this->is_field_agent == true;
    }
}
