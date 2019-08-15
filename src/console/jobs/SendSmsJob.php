<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 25/10/18
 * Time: 17:00
 */

namespace console\jobs;


use backend\modules\auth\Session;
use backend\modules\conf\models\SmsOutbox;
use backend\modules\conf\settings\SmsSettings;
use common\helpers\DateUtils;
use common\helpers\Msisdn;
use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;

class SendSmsJob extends BaseObject implements JobInterface
{
    /**
     * @var string
     */
    public $senderId;

    /**
     * @var string
     */
    public $msisdn;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var int
     */
    public $createdBy;

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var string
     */
    public $smsId;

    /**
     * @var string
     */
    public $apiKey;

    public function init()
    {
        parent::init();
    }


    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $this->msisdn = Msisdn::format($this->msisdn);
        try {
            $result = $this->doPost();
            $send_status = null;

            if (!empty($this->smsId)) {
                $model = SmsOutbox::loadModel($this->smsId);
                $model->attempts += 1;
            } else {
                $model = new SmsOutbox();
                $model->msisdn = $this->msisdn;
                $model->message = $this->message;
                $model->sender_id = $this->senderId;
                $model->created_at = $this->createdAt;
                $model->created_by = $this->createdBy;
            }
            $model->response_code = $result['statusCode'] ?? null;
            $model->response_remarks = $result['description'] ?? null;
            $deliveryReport = $result['deliveryReport'] ?? null;
            if (!empty($deliveryReport)) {
                $model->send_status = SmsOutbox::SEND_STATUS_SUCCESS;
            } else {
                $model->send_status = SmsOutbox::SEND_STATUS_FAILED;
            }
            $model->save(false);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * @param mixed $params
     * @return mixed
     */
    public static function push($params)
    {
        try {
            /* @var $queue \yii\queue\cli\Queue */
            $queue = Yii::$app->queue;
            if ($params instanceof self) {
                $obj = $params;
            } else {
                if (empty($params['createdBy']) && Yii::$app instanceof \yii\web\Application) {
                    $params['createdBy'] = Session::getUserId();
                }
                if (empty($params['createdAt'])) {
                    $params['createdAt'] = DateUtils::mysqlTimestamp();
                }
                if (empty($params['baseUrl'])) {
                    $params['baseUrl'] = SmsSettings::getBaseUrl();
                }
                if (empty($params['senderId'])) {
                    $params['senderId'] = SmsSettings::getDefaultSenderId();
                }
                if (empty($params['apiKey'])) {
                    $params['apiKey'] = SmsSettings::getApiKey();
                }

                $obj = new self($params);
            }

            $id = $queue->push($obj);

            return $id;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    protected function doPost()
    {
        $ch = curl_init();
        $payload = json_encode([
            'message' => $this->message,
            'senderId' => $this->senderId,
            'recipients' => $this->msisdn,
        ]);
        $headers = [
            'Content-Type: application/json',
            'ApiKey: ' . $this->apiKey,
        ];
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);

        curl_close($ch);
        return json_decode($output, true);
    }
}