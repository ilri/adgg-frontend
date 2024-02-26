<?php

namespace backend\modules\conf\models;

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\models\Users;
use backend\modules\auth\models\UsersNotificationSettings;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use console\jobs\SendEmail;
use console\jobs\SendSmsJob;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "conf_notif".
 *
 * @property integer $id
 * @property string $notif_type_id
 * @property integer $user_id
 * @property integer $item_id
 * @property integer $is_read
 * @property integer $is_seen
 * @property string $created_at
 */
class Notif extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%conf_notif}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notif_type_id', 'user_id', 'item_id'], 'required'],
            [['user_id', 'item_id', 'is_read', 'is_seen'], 'integer'],
            [['notif_type_id'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('ID'),
            'notif_type_id' => Lang::t('Notif Type'),
            'user_id' => Lang::t('User'),
            'item_id' => Lang::t('Item'),
            'is_read' => Lang::t('Read'),
            'created_at' => Lang::t('Date'),
        ];
    }

    /**
     * Pushes a new notification
     * @param string $notifTypeId
     * @param int $itemId
     * @param array $userIds
     * @param integer $createdBy
     * @param bool $enableInternalNotif
     * @param bool $enableEmailNotif
     * @param bool $enableSmsNotif
     * @return bool
     */
    public static function pushNotif($notifTypeId, $itemId, $userIds = [], $createdBy = null, $enableInternalNotif = null, $enableEmailNotif = null, $enableSmsNotif = null)
    {
        try {
            $notifType = NotifTypes::loadModel(['id' => $notifTypeId, 'is_active' => 1], false);
            if (null === $notifType) {
                Yii::warning(strtr('notif_type_id {id} does not exist', ['{id}' => $notifTypeId]));
                return false;
            }
            $globalUserIds = static::getNotifUsers($notifTypeId);
            $userIds = array_merge($globalUserIds, $userIds);

            if (is_null($enableInternalNotif) || $enableInternalNotif) {
                $enableInternalNotif = $notifType->enable_internal_notification;
            }
            if (is_null($enableEmailNotif) || $enableEmailNotif) {
                $enableEmailNotif = $notifType->enable_email_notification;
            }
            if (is_null($enableSmsNotif) || $enableSmsNotif) {
                $enableSmsNotif = $notifType->enable_sms_notification;
            }
            //process email internal
            if ($enableInternalNotif) {
                self::processInternal($notifType, $userIds, $itemId, $createdBy);
            }
            //process email
            if ($enableEmailNotif) {
                self::processEmail($notifType, $userIds, $itemId, $createdBy);
            }
            //process sms
            if ($enableSmsNotif) {
                self::processSms($notifType, $userIds, $itemId, $createdBy);
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $trace = $e->getTraceAsString();
            Yii::error($message);
            Yii::error($trace);
        }
    }

    /**
     *
     * @param NotifTypes $notifType
     * @param array $userIds
     * @param string $itemId
     * @param int|null $createdBy
     * @return void
     * @throws \yii\db\Exception
     */
    private static function processInternal($notifType, $userIds, $itemId, $createdBy = null)
    {
        $notif_data = [];
        $created_at = new Expression('NOW()');
        foreach ($userIds as $k => $userId) {
            if (!UsersNotificationSettings::isInternalNotifEnabled($userId, $notifType->id)) {
                continue;
            }
            if (!YII_DEBUG && $userId == $createdBy) {
                //continue;
            }
            $notif_data[] = [
                'notif_type_id' => $notifType->id,
                'user_id' => $userId,
                'item_id' => $itemId,
                'created_at' => $created_at,
            ];
        }

        static::insertMultiple($notif_data);
    }

    /**
     *
     * @param NotifTypes $notifType
     * @param array $userIds
     * @param string $itemId
     * @param int|null $createdBy
     * @return bool
     * @throws \Exception
     */
    private static function processEmail($notifType, $userIds, $itemId, $createdBy = null)
    {
        if (empty($userIds))
            return false;
        foreach ($userIds as $k => $userId) {
            if (!UsersNotificationSettings::isEmailNotifEnabled($userId, $notifType->id)) {
                unset($userIds[$k]);
                continue;
            }
            if (!YII_DEBUG && $userId == $createdBy) {
                //unset($userIds[$k]);
            }
        }

        $model_class_name = $notifType->model_class_name;
        /* @var $model \console\jobs\NotifInterface */
        $model = new $model_class_name();
        $emailParams = $model->processEmailTemplate($notifType, $itemId);

        if (!empty($emailParams)) {
            static::sendEmail($emailParams, $userIds, $notifType->email);
        }
    }

    /**
     *
     * @param NotifTypes $notifType
     * @param array $userIds
     * @param string $itemId
     * @param int|null $createdBy
     * @return bool
     * @throws \Exception
     */
    private static function processSms($notifType, $userIds, $itemId, $createdBy = null)
    {
        if (empty($userIds))
            return false;
        foreach ($userIds as $k => $userId) {
            if (!UsersNotificationSettings::isSmsNotifEnabled($userId, $notifType->id)) {
                unset($userIds[$k]);
                continue;
            }
            if (!YII_DEBUG && $userId == $createdBy) {
                //unset($userIds[$k]);
            }
        }
        $model_class_name = $notifType->model_class_name;
        /* @var $model \console\jobs\NotifInterface */
        $model = new $model_class_name();
        $template = $model->processSmsTemplate($notifType, $itemId);
        if (!empty($template)) {
            static::sendSms($userIds, $template['message']);
        }
    }

    /**
     * Send notification as an sms
     * @param array $userIds
     * @param string $message
     * @param null|array $msisdns
     * @throws \Exception
     */
    private static function sendSms($userIds, $message, $msisdns = [])
    {
        if (!empty($msisdns) && is_string($msisdns)) {
            $msisdns = explode(',', $msisdns);
        }

        if (!empty($userIds)) {
            $msisdns = array_merge((array)$msisdns, Users::getColumnData('phone', ['id' => $userIds]));
        }

        if (!empty($msisdns)) {
            $msisdns = array_unique($msisdns);
            foreach ($msisdns as $msisdn) {
                $sms = [
                    'message' => $message,
                    'msisdn' => $msisdn,
                ];
                SendSmsJob::push($sms);
            }
        }
    }

    /**
     * Update notification
     * System triggered notifications
     * This method should be run in console application via cron (every hour is the recommended cron frequency)
     */
    public static function createNotifications()
    {
        $rowset = NotifTypes::getColumnData('model_class_name', ['notification_trigger' => NotifTypes::TRIGGER_SYSTEM, 'is_active' => 1]);
        foreach ($rowset as $model_class) {
            /* @var $model \console\jobs\NotifInterface */
            try {
                $model = new $model_class();
                $model->createSystemNotifications();
            } catch (\Exception $e) {
                Yii::error($e->getTraceAsString());
            }
        }
    }

    /**
     * Get notification date
     * @param string $notif_type_id
     * @return string $date
     * @throws \Exception
     */
    public static function getNotificationDate($notif_type_id)
    {
        $notify_days_before = NotifTypes::getFieldByPk($notif_type_id, 'notify_days_before');
        if (empty($notify_days_before)) {
            return date('Y-m-d');
        }

        return DateUtils::addDate(date('Y-m-d'), (int)$notify_days_before, 'day');
    }

    /**
     * Get users who are supposed to receive a notification
     * @param string $notif_type_id
     * @return array $users
     * @throws \Exception
     */
    public static function getNotifUsers($notif_type_id)
    {
        $notify_all_users = NotifTypes::getFieldByPk($notif_type_id, 'notify_all_users');

        if ($notify_all_users)
            return Users::getColumnData('id', 'status=:t1 AND level_id<=:t2', [':t1' => Users::STATUS_ACTIVE, ':t2' => UserLevels::LEVEL_ADMIN]);

        $users = NotifTypes::getScalar('users', ['id' => $notif_type_id]);
        if (!empty($users)) {
            $users = unserialize($users);
            if (!is_array($users)) {
                $users = explode(',', $users);
            }
        } else {
            $users = [];
        }
        $roles = NotifTypes::getScalar('roles', ['id' => $notif_type_id]);
        if (!empty($roles)) {
            $roles = unserialize($roles);
            if (!is_array($roles)) {
                $roles = explode(',', $roles);
            }
            $users = array_merge($users, Users::getColumnData('id', ['role_id' => $roles]));
        }
        return array_unique($users);
    }

    /**
     * Send notification as an email
     * @param array $emailParams
     * @param array $userIds
     * @param array|string $emailAddresses
     * @throws \Exception
     */
    private static function sendEmail($emailParams, $userIds, $emailAddresses = null)
    {
        if (!empty($emailAddresses) && is_string($emailAddresses)) {
            $emailAddresses = explode(',', $emailAddresses);
        }

        if (!empty($userIds)) {
            $emailAddresses = array_merge((array)$emailAddresses, Users::getColumnData('email', ['id' => $userIds]));
        }

        if (!empty($emailAddresses)) {
            $emailAddresses = array_unique($emailAddresses);
            foreach ($emailAddresses as $e) {
                $email = $emailParams;
                $email['recipient_email'] = trim($e);
                SendEmail::push($email);
            }
        }
    }

    /**
     * Fetch notification
     * @param string $user_id
     * @return array
     * @throws \Exception
     */
    public static function fetchNotif($user_id = NULL)
    {
        if (empty($user_id))
            $user_id = Yii::$app->user->id;
        return static::getData('*', ['user_id' => $user_id], [], ['orderBy' => ['id' => SORT_DESC], 'limit' => 100]);
    }

    /**
     *
     * @param string $user_id
     * @return int
     * @throws \Exception
     */
    public static function getTotalUnSeenNotif($user_id = NULL)
    {
        if (empty($user_id))
            $user_id = Yii::$app->user->id;
        return static::getCount(['user_id' => $user_id, 'is_seen' => 0]);
    }

    /**
     *
     * @param string $notif_type_id
     * @param string $item_id
     * @return array|bool $processed_template
     * @throws \Exception
     */
    public static function processTemplate($notif_type_id, $item_id)
    {
        try {
            $notif_type = NotifTypes::getOneRow(['template', 'model_class_name'], ['id' => $notif_type_id]);
            if (empty($notif_type))
                return false;
            $model_class_name = $notif_type['model_class_name'];
            /* @var $model \console\jobs\NotifInterface */
            $model = new $model_class_name();
            return $model::processInternalTemplate($notif_type['template'], $item_id, $notif_type_id);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }
    }

    /**
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function markAsSeen()
    {
        return Yii::$app->db->createCommand()
            ->update(static::tableName(), ['is_seen' => 1], ['user_id' => Yii::$app->user->id])
            ->execute();
    }

    /**
     *
     * @param string $id
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function markAsRead($id = NULL)
    {
        $condition = ['user_id' => Yii::$app->user->id];
        if (!empty($id)) {
            $condition['id'] = $id;
        }

        return Yii::$app->db->createCommand()
            ->update(static::tableName(), ['is_read' => 1], $condition)
            ->execute();
    }

}
