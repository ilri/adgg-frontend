<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-01
 * Time: 2:56 AM
 */

namespace console\jobs;


use backend\modules\conf\models\Notif;
use backend\modules\core\models\OdkJsonQueue;
use backend\modules\core\models\OrganizationNotifSettings;
use Yii;
use yii\queue\Queue;

class ODKJsonNotification extends BaseNotification implements JobInterface, NotifInterface
{

    const NOTIF_ODK_JSON = 'odk_json';

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function execute($queue)
    {
        $model = OdkJsonQueue::loadModel($this->item_id, false);
        if ($model !== null) {
            $settings = OrganizationNotifSettings::getSettings(10, $this->notif_type_id);
            Notif::pushNotif($this->notif_type_id, $this->item_id, $settings->users, $this->created_by, $settings->enable_internal_notification, $settings->enable_email_notification, $settings->enable_sms_notification);
        }
    }

    public static function createSystemNotifications()
    {
        return false;
    }

    /**
     * @param string $itemId
     * @param string $messageTemplate
     * @param null|string $subjectTemplate
     * @return array|bool
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public static function processTemplate($itemId, $messageTemplate, $subjectTemplate = null)
    {
        //placeholders:{status},{url}
        $model = OdkJsonQueue::loadModel($itemId, false);
        if ($model === null)
            return false;

        $url = Yii::$app->getUrlManager()->createAbsoluteUrl(['/core/odk-json/index']);
        $status = $model->has_errors ? 'Has Errors' : 'Success';
        $message = strtr($messageTemplate, [
            '{uuid}' => $model->uuid,
            '{status}' => $status,
            '{url}' => $url,
        ]);

        $subject = null;
        if (!empty($subjectTemplate)) {
            $subject = strtr($subjectTemplate, [
                '{uuid}' => $model->uuid,
                '{status}' => $status,
            ]);
        }

        return [
            'subject' => $subject,
            'message' => $message,
            'url' => $url,
        ];
    }
}