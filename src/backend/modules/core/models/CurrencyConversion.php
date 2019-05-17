<?php

namespace backend\modules\core\models;

use common\helpers\DbUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use common\widgets\lineItem\LineItem;
use common\widgets\lineItem\LineItemModelInterface;
use common\widgets\lineItem\LineItemTrait;
use yii\bootstrap\Html;

/**
 * This is the model class for table "core_master_currency_conversion".
 *
 * @property int $id
 * @property string $default_currency
 * @property string $currency
 * @property string $conversion_rate
 * @property int $is_active
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property Organization $org
 */
class CurrencyConversion extends ActiveRecord implements ActiveSearchInterface, LineItemModelInterface
{
    use ActiveSearchTrait, LineItemTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%core_master_currency_conversion}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['default_currency', 'currency', 'conversion_rate'], 'required'],
            [['is_active'], 'integer'],
            [['conversion_rate'], 'number', 'min' => 0],
            [['default_currency', 'currency'], 'string', 'max' => 3],
            ['conversion_rate', 'unique', 'targetAttribute' => ['default_currency', 'currency'], 'message' => Lang::t('{attribute} is already defined.')],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Lang::t('ID'),
            'default_currency' => Lang::t('Default Currency'),
            'currency' => Lang::t('Currency'),
            'conversion_rate' => Lang::t('Conversion Rate'),
            'is_active' => Lang::t('Active'),
            'created_at' => Lang::t('Created At'),
            'created_by' => Lang::t('Created By'),
            'updated_at' => Lang::t('Updated At'),
            'updated_by' => Lang::t('Updated By'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function searchParams()
    {
        return [
            'default_currency',
            'currency',
            'is_active',
        ];
    }

    /**
     *  {@inheritdoc}
     */
    public function lineItemFields()
    {
        return [
            [
                'attribute' => 'currency',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_STATIC,
                'value' => function (CurrencyConversion $model) {
                    return '<span>' . Html::encode(Currency::getCurrencyAttribute($model->currency, 'name')) . ' (' . Html::encode($model->currency) . ')' . '</span>';
                },
                'tdOptions' => [],
                'options' => [],
            ],
            [
                'attribute' => 'conversion_rate',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_CHECKBOX,
                'tdOptions' => ['class' => 'text-left'],
                'options' => ['class' => ''],
                'input' => function (CurrencyConversion $model) {
                    /* @var $lineItem LineItem */
                    $lineItem = $this;
                    $template = '<div class="input-group" style="max-width: 300px;"><span class="input-group-addon">1 {currency} = </span>{input}<span class="input-group-addon">{default_currency}</span></div>';
                    $options = $lineItem->getActiveInputOptions($model, 'conversion_rate', $this->nextItemIndex, ['class' => 'form-control']);
                    return strtr($template, [
                        '{currency}' => $model->currency,
                        '{default_currency}' => $model->default_currency,
                        '{input}' => Html::activeTextInput($model, 'conversion_rate', $options),
                    ]);
                },
            ],
            [
                'attribute' => 'default_currency',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_HIDDEN_INPUT,
                'tdOptions' => [],
                'options' => [],
            ],
            [
                'attribute' => 'currency',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_HIDDEN_INPUT,
                'tdOptions' => [],
                'options' => [],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function lineItemFieldsLabels()
    {
        return [
            ['label' => $this->getAttributeLabel('currency'), 'options' => []],
            ['label' => $this->getAttributeLabel('conversion_rate'), 'options' => ['class' => 'text-left']],
            ['label' => '&nbsp;', 'options' => []],
        ];
    }


    public static function getModels($defaultCurrency)
    {
        $currencies = Currency::getListData('iso3', 'iso3', false, '[[iso3]]<>:iso3', [':iso3' => $defaultCurrency]);
        $models = [];
        $condition = '[[default_currency]]=:default_currency';
        $params = [':default_currency' => $defaultCurrency];

        foreach ($currencies as $currency => $currencyName) {
            list($newCondition, $newParams) = DbUtils::appendCondition('currency', $currency, $condition, $params);
            $model = static::find()->andWhere($newCondition, $newParams)->one();
            if ($model === null) {
                $model = new static([
                    'default_currency' => $defaultCurrency,
                    'currency' => $currency,
                ]);
            }
            $models[] = $model;
        }

        return $models;
    }

    public function afterFind()
    {
        $this->conversion_rate = (float)$this->conversion_rate;
        parent::afterFind();
    }

    /**
     * @param string $defaultCurrency
     * @param string $currency
     * @return float
     * @throws \Exception
     */
    public static function getConversionRate($defaultCurrency, $currency)
    {
        if ($defaultCurrency === $currency) {
            return 1;
        }
        $condition = ['default_currency' => $defaultCurrency, 'currency' => $currency, 'is_active' => 1];
        $conversionRate = (float)static::getScalar('conversion_rate', $condition);
        if ($conversionRate > 0) {
            return $conversionRate;
        }
        //inverted
        $condition['default_currency'] = $currency;
        $condition['currency'] = $defaultCurrency;
        $conversionRate = (float)static::getScalar('conversion_rate', $condition);
        if ($conversionRate > 0) {
            return round((1 / $conversionRate), 4);
        }

        return null;
    }


}
