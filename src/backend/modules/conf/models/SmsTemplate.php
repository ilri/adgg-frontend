<?php

namespace backend\modules\conf\models;

use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;

/**
 * This is the model class for table "sms_template".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $template
 * @property string $available_placeholders
 * @property string $created_at
 * @property int $created_by
 */
class SmsTemplate extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms_template}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'template'], 'required'],
            [['code'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 255],
            [['template', 'available_placeholders'], 'string', 'max' => 1000],
            [['code'], 'unique'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('ID'),
            'code' => Lang::t('Code'),
            'name' => Lang::t('Name'),
            'template' => Lang::t('Template'),
            'available_placeholders' => Lang::t('Available Placeholders'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function searchParams()
    {
        return [
            ['code', 'code'],
            ['name', 'name'],
        ];
    }
}
