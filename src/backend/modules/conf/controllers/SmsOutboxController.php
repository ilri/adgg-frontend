<?php
/**
 * Created by PhpStorm.
 * User: fred
 * Date: 25/10/18
 * Time: 18:50
 */

namespace backend\modules\conf\controllers;


use backend\modules\auth\Acl;
use backend\modules\conf\Constants;
use backend\modules\conf\models\SmsOutbox;
use yii\web\NotFoundHttpException;

class SmsOutboxController extends Controller
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->activeSubMenu = Constants::SUBMENU_SMS;
        $this->resourceLabel = 'SMS Outbox';
    }

    public function actionIndex()
    {
        $searchModel = SmsOutbox::searchModel([
            'defaultOrder' => ['id' => SORT_DESC]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
        ]);
    }

    public function actionResend($id)
    {
        $this->hasPrivilege(Acl::ACTION_UPDATE);

        SmsOutbox::resendSms($id);

        return json_encode(['success' => true, 'message' => 'Message has been resent successfully']);
    }

}