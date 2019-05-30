<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-05-27
 * Time: 12:44 AM
 */

namespace backend\modules\core\models;


use common\helpers\DateUtils;
use common\widgets\lineItem\LineItem;
use common\widgets\lineItem\LineItemModelInterface;
use common\widgets\lineItem\LineItemTrait;
use yii\web\View;

class RegistrationDocumentLine extends RegistrationDocument implements LineItemModelInterface
{

    use LineItemTrait;

    /**
     * Sample return value
     * ```php
     * return $fields = [
     *    ['attribute'=>'name','type'=>LineItem::LINE_ITEM_FIELD_TYPE_TEXT_INPUT,'options'=>['class'=>'form-control'],'tdOptions'=>[]],
     *    ['attribute'=>'category_id','type'=>LineItem::LINE_ITEM_FIELD_TYPE_DROP_DOWN_LIST,'listItems'=>Category::getListData(),'options'=>['class'=>'form-control']],
     *    ['attribute'=>'price','type'=>LineItem::LINE_ITEM_FIELD_TYPE_TEXT_INPUT,'options'=>['class'=>'form-control'],'template'=>"<div class='input-group'><span class='input-group-addon'>USD</span>{input}</div>"],
     * ];
     * ```
     * @return array
     */
    public function lineItemFields()
    {
        return [
            [
                'attribute' => 'file_name',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_FILE,
                'options' => ['class' => 'file-field'],
                'tdOptions' => ['style' => 'max-width:300px;'],
                'widget' => function (RegistrationDocumentLine $model, View $view, $index) {
                    $html = $view->render('@authModule/views/auth/register/_fileField', ['model' => $model, 'index' => $index]);

                    return $html;
                },
            ],
            [
                'attribute' => 'doc_type_id',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_DROP_DOWN_LIST,
                'options' => ['class' => 'form-control'],
                'tdOptions' => [],
                'listItems' => function (RegistrationDocumentLine $model) {
                    return RegistrationDocumentType::getOrgListData();
                }
            ],
            [
                'attribute' => 'renewal_date',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_TEXT_INPUT,
                'tdOptions' => [],
                'options' => ['class' => 'form-control show-datepicker', 'data-min-date' => DateUtils::getToday()],
            ],
            [
                'attribute' => 'org_id',
                'type' => LineItem::LINE_ITEM_FIELD_TYPE_HIDDEN_INPUT,
                'tdOptions' => [],
                'options' => [],
            ],
        ];
    }

    /**
     * Sample return value
     * ```php
     * $labels = [
     *    ['label'=>'Name','options'=>['class'=>'text-bold']],
     *    ['label'=>'Description','options'=>['class'=>'text-bold']],
     * ];
     * //OR
     * $labels = [
     *    'Name',
     *    'Description',
     * ];
     * ```
     * @return array
     */
    public function lineItemFieldsLabels()
    {
        return [
            ['label' => $this->getAttributeLabel('file_name') . ' (required)', 'options' => []],
            ['label' => $this->getAttributeLabel('doc_type_id') . ' (required)', 'options' => []],
            ['label' => $this->getAttributeLabel('renewal_date') . ' (optional)', 'options' => []],
            ['label' => '&nbsp;', 'options' => []],
        ];
    }
}