<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-17
 * Time: 1:51 PM
 */

namespace console\jobs;


use backend\modules\conf\models\Notif;
use backend\modules\conf\models\NotifTypes;
use backend\modules\core\models\ExcelImport;
use Yii;
use yii\queue\Queue;

class ExcelUploadNotification extends BaseNotification implements JobInterface, NotifInterface
{
    const NOTIF_EXCEL_UPLOAD_COMPLETION = 'excel_upload_completion';

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws \yii\web\NotFoundHttpException
     */
    public function execute($queue)
    {
        $model = ExcelImport::loadModel($this->item_id);
        Notif::pushNotif($this->notif_type_id, $this->item_id, [$model->created_by], $this->created_by);
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
     */
    public static function processInternalTemplate($template, $item_id, $notif_type_id)
    {
        $notifModel = NotifTypes::loadModel($notif_type_id, false);
        if (null === $notifModel) {
            return false;
        }
        return static::processTemplate($item_id, $template, $notifModel->name);
    }

    /**
     * @param string $itemId
     * @param string $messageTemplate
     * @param null|string $subjectTemplate
     * @return array|bool
     * @throws \yii\web\NotFoundHttpException
     */
    public static function processTemplate($itemId, $messageTemplate, $subjectTemplate = null)
    {
        //placeholders:{admin},{file_name},{url}
        $model = ExcelImport::loadModel($itemId);
        $url = Yii::$app->getUrlManager()->createAbsoluteUrl(['/core/excel-upload-status/index', 'id' => $model->uuid]);

        $admin = $model->getRelationAttributeValue('createdByUser', 'name');
        $message = strtr($messageTemplate, [
            '{admin}' => $admin,
            '{file_name}' => $model->file_name,
            '{url}' => $url,
        ]);

        $subject = null;
        if (!empty($subjectTemplate)) {
            $subject = strtr($subjectTemplate, [
                '{admin}' => $admin,
                '{file_name}' => $model->file_name,
            ]);
        }

        return [
            'subject' => $subject,
            'message' => $message,
            'url' => $url,
        ];
    }
}