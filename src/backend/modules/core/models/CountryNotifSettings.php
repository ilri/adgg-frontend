<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\models\Users;
use backend\modules\conf\models\NotifTypes;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\widgets\lineItem\LineItem;
use common\widgets\lineItem\LineItemModelInterface;
use common\widgets\lineItem\LineItemTrait;
use yii\helpers\Html;

/**
 * This is the model class for table "country_notif_settings".
 *
 * @property int $id
 * @property int $country_id
 * @property string $notification_id
 * @property int $enable_internal_notification
 * @property int $enable_email_notification
 * @property int $enable_sms_notification
 * @property string $users
 * @property string $roles
 * @property string $email
 * @property string $phone
 * @property string $created_at
 * @property int $created_by
 *
 * @property Country $country
 * @property NotifTypes $notification
 */
class CountryNotifSettings extends ActiveRecord implements LineItemModelInterface
{
    use LineItemTrait, CountryDataTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%country_notif_settings}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id', 'notification_id'], 'required'],
            [['country_id', 'enable_internal_notification', 'enable_email_notification', 'enable_sms_notification'], 'integer'],
            [['users', 'roles'], 'safe'],
            [['notification_id'], 'string', 'max' => 60],
            [['email', 'phone'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('ID'),
            'country_id' => Lang::t('Country ID'),
            'notification_id' => Lang::t('Notification ID'),
            'enable_internal_notification' => Lang::t('Enable Internal Notification'),
            'enable_email_notification' => Lang::t('Enable Email Notification'),
            'enable_sms_notification' => Lang::t('Enable Sms Notification'),
            'users' => Lang::t('Users'),
            'roles' => Lang::t('Roles'),
            'email' => Lang::t('Email'),
            'phone' => Lang::t('Phone'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(NotifTypes::class, ['id' => 'notification_id']);
    }

    /**
     *  {@inheritdoc}
     */
    public function lineItemFields()
    {
        return [
            [
                'attribute' => 'notification_id',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_STATIC,
                'value' => function (CountryNotifSettings $model) {
                    return '<span>' . Html::encode($model->notification->name) . '<br/><small class="text-muted">' . Html::encode($model->notification->description) . '</small>' . '</span>';
                },
                'tdOptions' => ['style' => 'max-width:400px;'],
                'options' => [],
            ],
            [
                'attribute' => 'notification_id',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_HIDDEN_INPUT,
                'tdOptions' => [],
                'options' => [],
            ],
            [
                'attribute' => 'country_id',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_HIDDEN_INPUT,
                'tdOptions' => [],
                'options' => [],
            ],
            [
                'attribute' => 'enable_internal_notification',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_CHECKBOX,
                'tdOptions' => [],
                'options' => [],
            ],
            [
                'attribute' => 'enable_email_notification',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_CHECKBOX,
                'tdOptions' => [],
                'options' => [],
            ],
            [
                'attribute' => 'enable_sms_notification',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_CHECKBOX,
                'tdOptions' => [],
                'options' => [],
            ],
            [
                'attribute' => 'users',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_DROP_DOWN_LIST,
                'listItems' => function (CountryNotifSettings $model) {
                    return Users::getListData('id', 'name', false, ['level_id' => UserLevels::LEVEL_COUNTRY, 'country_id' => $model->country_id]);
                },
                'tdOptions' => ['style' => 'min-width:300px;'],
                'options' => ['class' => 'form-control select2', 'multiple' => true],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function lineItemFieldsLabels()
    {
        return [
            ['label' => $this->getAttributeLabel('notification_id'), 'options' => []],
            ['label' => $this->getAttributeLabel('enable_internal_notification'), 'options' => []],
            ['label' => $this->getAttributeLabel('enable_email_notification'), 'options' => []],
            ['label' => $this->getAttributeLabel('enable_sms_notification'), 'options' => []],
            ['label' => $this->getAttributeLabel('users'), 'options' => []],
            ['label' => '&nbsp;', 'options' => []],
        ];
    }

    public function beforeSave($insert)
    {
        if (!empty($this->users)) {
            $this->users = serialize($this->users);
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        if (!empty($this->users)) {
            $this->users = unserialize($this->users);
        }
        parent::afterFind();
    }


    /**
     * @param int $country_id
     * @param string $notification_id
     * @return CountryNotifSettings|null
     */
    public static function getModel($country_id, $notification_id)
    {
        return static::findOne(['country_id' => $country_id, 'notification_id' => $notification_id]);
    }

    /**
     * @param int $country_id
     * @param string $notification_id
     * @return bool|int
     */
    public static function isInternalNotifEnabled($country_id, $notification_id)
    {
        $model = static::getModel($country_id, $notification_id);
        if (null === $model) {
            return true;
        }
        return $model->enable_internal_notification;
    }

    /**
     * @param int $country_id
     * @param string $notification_id
     * @return bool|int
     */
    public static function isEmailNotifEnabled($country_id, $notification_id)
    {
        $model = static::getModel($country_id, $notification_id);
        if (null === $model) {
            return true;
        }
        return $model->enable_email_notification;
    }

    /**
     * @param int $country_id
     * @param string $notification_id
     * @return bool|int
     */
    public static function isSmsNotifEnabled($country_id, $notification_id)
    {
        $model = static::getModel($country_id, $notification_id);
        if (null === $model) {
            return true;
        }
        return $model->enable_sms_notification;
    }

    /**
     * @param int $country_id
     * @param string $notification_id
     * @return $this
     * @throws \Exception
     */
    public static function getSettings($country_id, $notification_id)
    {
        $model = static::getModel($country_id, $notification_id);
        $enableInternalNotif = null;
        $enableEmailNotif = null;
        $enableSmsNotif = null;
        $userIds = Users::getColumnData('id', ['level_id' => UserLevels::LEVEL_COUNTRY, 'country_id' => $country_id, 'is_main_account' => 1]);
        if (null !== $model) {
            if (empty($model->users)) {
                $model->users = $userIds;
            }
        } else {
            $model = new static([
                'users' => $userIds,
                'enable_internal_notification' => null,
                'enable_email_notification' => null,
                'enable_sms_notification' => null,
            ]);
        }

        return $model;
    }

}
