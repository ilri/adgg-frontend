<?php
/**
 * Created by PhpStorm.
 * @author Fred <mconyango@gmail.com>
 * Date: 2018-07-11
 * Time: 22:13
 */

namespace console\jobs;


use backend\modules\conf\models\NotifQueue;
use Yii;
use yii\base\BaseObject;

class BaseNotification extends BaseObject
{

    /**
     * @var string
     */
    public $notif_type_id;

    /**
     * @var integer
     */
    public $item_id;

    /**
     * @var integer
     */
    public $created_by;

    /**
     * @param mixed $params
     * @return mixed
     */
    public static function push($params)
    {
        /* @var $queue \yii\queue\cli\Queue */
        $queue = Yii::$app->queue;
        if ($params instanceof static) {
            $obj = $params;
        } else {
            $obj = new static($params);
        }

        $id = $queue->push($obj);

        return $id;
    }

    /**
     * @param array $item_ids
     * @param string $notif_type_id
     * @throws \yii\web\NotFoundHttpException
     */
    protected static function pushCreatedNotification($item_ids, $notif_type_id)
    {
        if (!empty($item_ids)) {
            foreach ($item_ids as $item_id) {
                if (NotifQueue::push($notif_type_id, $item_id)) {
                    //push notification to the queue for processing
                    static::push([
                        'notif_type_id' => $notif_type_id,
                        'item_id' => $item_id,
                        'created_by' => null,
                    ]);
                }
            }
        }
    }

    /**
     * @param string $notif_type_id
     * @param string $item_id
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function createManualNotifications($notif_type_id, $item_id)
    {
        if (NotifQueue::push($notif_type_id, $item_id)) {
            //push notification to the queue for processing
            static::push([
                'notif_type_id' => $notif_type_id,
                'item_id' => $item_id,
                'created_by' => Yii::$app instanceof \yii\web\Application && !Yii::$app->user->isGuest ? Yii::$app->user->id : null,
            ]);
        }
    }
}