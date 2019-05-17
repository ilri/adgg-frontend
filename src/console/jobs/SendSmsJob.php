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
    public $sender_id;

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
    public $transaction_id;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var int
     */
    public $created_by;

    /**
     * @var string
     */
    private $_baseUrl = 'https://testgateway.ekenya.co.ke:8443/ServiceLayer/pgsms/send';
    /**
     * @var string
     */
    private $_getUrl;

    public $sms_id;

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
        $url = $this->_baseUrl;
        $url .= '?to={msisdn}&message={message}&from={sender_id}&transactionID={transactionId}';
        $this->_getUrl = strtr($url, [
            '{msisdn}' => urlencode($this->msisdn),
            '{message}' => urlencode($this->message),
            '{sender_id}' => urlencode($this->sender_id),
            '{transactionId}' => urlencode($this->transaction_id),
        ]);

        try {
            $result = $this->doPost();

            $send_status = null;

            if(!empty($this->sms_id)){
                $model=SmsOutbox::loadModel($this->sms_id);
                $model->attempts+=1;
            }else{
                $model = new SmsOutbox();
                $model->msisdn = $this->msisdn;
                $model->message = $this->message;
                $model->sender_id = $this->sender_id;
                $model->created_at = $this->created_at;
                $model->created_by = $this->created_by;
            }
            $model->response_code = $result->ResultCode;
            $model->response_remarks = $result->ResultDesc;
            if ($result->ResultCode == 0) {
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
                if (empty($params['created_by']) && Yii::$app instanceof \yii\web\Application)
                    $params['created_by'] = Session::userId();
                if (empty($params['created_at']))
                    $params['created_at'] = DateUtils::mysqlTimestamp();
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

        curl_setopt($ch, CURLOPT_URL, $this->_getUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);

        curl_close($ch);
        return json_decode($output);
    }
}