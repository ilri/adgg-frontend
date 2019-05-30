<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-01-23 18:04
 * Time: 18:04
 */

namespace backend\modules\core\controllers;


use backend\modules\conf\settings\SystemSettings;
use backend\modules\core\models\Currency;
use backend\modules\core\models\CurrencyConversion;
use common\widgets\lineItem\LineItem;

class CurrencyConversionController extends MasterDataController
{
    public function init()
    {
        parent::init();
        $this->resourceLabel = 'Currency Conversion Rates';
    }

    public function actionUpdate($default_currency = null)
    {
        if (empty($default_currency)) {
            $default_currency = SystemSettings::getDefaultCurrency();
        }
        $model = Currency::loadModel(['iso3' => $default_currency]);
        $lineItemModelClassName = CurrencyConversion::class;
        if ($resp = LineItem::finishAction($model, $lineItemModelClassName, 'default_currency', false, [
            'redirectRoute' => 'update',
            'redirectParams' => ['default_currency' => $default_currency],
            'idParam' => 'default_currency',
            'idParamAttribute' => 'iso3',
            'parentModelPrimaryKeyAttribute' => 'iso3',
        ])) {
            return $resp;
        }

        return $this->render('update', [
            'model' => $model,
            'lineItemModels' => CurrencyConversion::getModels($default_currency),
        ]);
    }
}