<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-07-01
 * Time: 2:56 AM
 */

namespace console\jobs;


use backend\modules\conf\models\EmailTemplate;
use backend\modules\conf\models\Notif;
use backend\modules\conf\models\NotifTypes;
use backend\modules\conf\models\SmsTemplate;
use backend\modules\conf\settings\SystemSettings;
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
     *
     * @param string $template
     * @param string $item_id
     * @param string $notif_type_id
     *
     * @return array|bool
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public static function processInternalTemplate($template, $item_id, $notif_type_id)
    {
        $notifModel = NotifTypes::loadModel($notif_type_id, false);
        if (null === $notifModel) {
            return false;
        }
        return self::processTemplate($item_id, $template, $notifModel->name);
    }

    /**
     * @param NotifTypes $notifType
     * @param string $itemId
     * @return array|bool
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public static function processEmailTemplate($notifType, $itemId)
    {
        $emailTemplateModel = EmailTemplate::loadModel($notifType->email_template_id, false);
        if (null === $emailTemplateModel) {
            return false;
        }

        $emailParams = [
            'sender_name' => SystemSettings::getAppName(),
            'sender_email' => $emailTemplateModel->sender,
            'template_id' => $notifType->email_template_id,
            'ref_id' => $itemId,
        ];
        $params = self::processTemplate($itemId, $emailTemplateModel->body, $emailTemplateModel->subject);
        $emailParams['subject'] = $params['subject'];
        $emailParams['message'] = $params['message'];

        return $emailParams;
    }

    /**
     * @param NotifTypes $notifType
     * @param string $itemId
     * @return array|bool
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public static function processSmsTemplate($notifType, $itemId)
    {
        $smsTemplateModel = SmsTemplate::loadModel($notifType->sms_template_id, false);
        if (null === $smsTemplateModel) {
            return false;
        }
        return self::processTemplate($itemId, $smsTemplateModel->template);
    }

    /**
     * @param string $itemId
     * @param string $messageTemplate
     * @param null|string $subjectTemplate
     * @return array|bool
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    private static function processTemplate($itemId, $messageTemplate, $subjectTemplate = null)
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