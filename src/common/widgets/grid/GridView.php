<?php

namespace common\widgets\grid;

use backend\modules\conf\settings\SystemSettings;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2015/12/01
 * Time: 8:43 PM
 *
 * Wrapper for gridview extension by Kartik Visweswaran <kartikv2@gmail.com>
 */
class GridView extends \kartik\grid\GridView
{
    /**
     * Boolean Icons
     */
    const ICON_ACTIVE = '<span class="fa fa-check text-success"></span>';
    const ICON_INACTIVE = '<span class="fa fa-check text-danger"></span>';

    /**
     * Expand Row Icons
     */
    const ICON_EXPAND = '<span class="fa fa-expand"></span>';
    const ICON_COLLAPSE = '<span class="fa fa-compress"></span>';
    const ICON_UNCHECKED = '<span class="fa fa-unchecked"></span>';

    public $options = [
        'class' => 'grid-view',
    ];

    public $pjax = true;
    public $condensed = false;
    public $hover = true;
    public $floatHeader = false;
    public $resizableColumns = false;
    public $bootstrap = true;
    public $bordered = true;
    public $striped = true;
    public $responsive = true;
    public $showPageSummary = false;
    public $title;
    public $panelTemplate = <<< HTML
    <div class="kt-portlet__head kt-portlet__head--lg">
    {panelHeading}
    {panelBefore}
</div>
{items}
{panelAfter}
{panelFooter}
HTML;
    public $panel = [
        'type' => GridView::TYPE_DEFAULT,
        'after' => false,
        'options' => ['class' => 'kt-portlet kt-portlet--mobile'],
        'headingOptions' => ['class' => 'kt-portlet__head-label'],
        'titleOptions' => ['class' => 'kt-portlet__head-title'],
    ];
    public $panelPrefix = '';
    public $persistResize = false;
    // set export properties
    public $export = [
        'fontAwesome' => true,
        'icon' => '',
        'label' => '<i class="la la-download"></i> EXPORT',
        'options' => ['class' => 'btn btn-default btn-bold btn-upper btn-font-sm'],
    ];

    public $headerRowOptions = ['class' => 'kartik-sheet-style'];
    public $filterRowOptions = ['class' => 'kartik-sheet-style'];
    public $beforeHeader = [];
    public $formatter = ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''];

    //custom attributes
    public $showRefreshButton = true;
    public $refreshUrl;
    public $showExportButton = true;
    /**
     * @var
     */
    public $toolbarButtons = [];

    public $downloadallButton = [];

    /**
     * @var ActiveRecord
     */
    public $searchModel;

    /**
     * @var
     */
    public $createButton = [];

    public $tableOptions = [];

    public $layouts = "{summary}\n{items}\n{pager}";

    public $layout = <<< HTML
<div class="kt-portlet kt-portlet--mobile" style="margin-bottom: 0;">
<div class="kt-portlet__head kt-portlet__head--lg">
<div class="kt-portlet__head-label">
 {title}
</div>
<div class="kt-portlet__head-toolbar">
{toolbarContainer}
</div>
</div>
<div class="kt-portlet__body kt-portlet__body--fit">
<div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--scroll kt-datatable--loaded">
{items}
<div class="kt-datatable__pager kt-datatable--paging-loaded">
{pager}
<div class="kt-datatable__pager-info">
<span class="kt-datatable__pager-detail">{summary}</span>
</div>
</div>
</div>
</div>
</div>
HTML;


    /**
     * @inheritdoc
     */
    public function init()
    {

        if (!empty($this->searchModel)) {
            $this->dataProvider = $this->searchModel->search();
            $this->id = $this->searchModel->getGridViewWidgetId();
        }

        if (empty($this->title))
            $this->title = $this->getView()->title;
        $this->panel['heading'] = $this->title;
        $this->setToolbar();
        $this->setReplaceTags();
        $this->toggleDataOptions = [
            'maxCount' => 10000,
            'minCount' => 500,
            'confirmMsg' => Lang::t(
                'There are {totalCount} records. Are you sure you want to display them all?',
                ['totalCount' => number_format($this->dataProvider->getTotalCount())]
            ),
            'all' => [
                'icon' => '',
                'label' => '<i class="fa fa-expand"></i> ' . Lang::t('All'),
                'class' => 'btn btn-default',
                'title' => Lang::t('Show all data')
            ],
            'page' => [
                'icon' => '',
                'label' => '<i class="fa fa-compress"></i> ' . Lang::t('Page'),
                'class' => 'btn btn-default',
                'title' => Lang::t('Show first page data')
            ],
        ];
        $this->exportConfig = [
            self::CSV => [],
            self::EXCEL => [],
            self::PDF => [],
            self::JSON => [],
            self::TEXT => [],
            self::HTML => [],
        ];
        $title = Lang::t('Grid Export');
        $pdfHeader = [
            'L' => [
                'content' => $this->title,
                'font-size' => 8,
                'color' => '#333333',
            ],
            'C' => [
                'content' => $title,
                'font-size' => 16,
                'color' => '#333333',
            ],
            'R' => [
                'content' => Lang::t('Generated') . ': ' . DateUtils::formatToLocalDate(date(time()), "D, d-M-Y g:i a"),
                'font-size' => 8,
                'color' => '#333333',
            ],
        ];
        $pdfFooter = [
            'L' => [
                'content' => Lang::t('© {app}', ['app' => SystemSettings::getAppName()]),
                'font-size' => 8,
                'font-style' => 'B',
                'color' => '#999999',
            ],
            'R' => [
                'content' => '[ {PAGENO} ]',
                'font-size' => 10,
                'font-style' => 'B',
                'font-family' => 'serif',
                'color' => '#333333',
            ],
            'line' => true,
        ];
        $this->exportConfig[self::PDF] = [
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'config' => [
                'mode' => 'UTF-8',
                'format' => 'A4-L',
                'destination' => 'D',
                'marginTop' => 20,
                'marginBottom' => 20,
                'cssInline' => '.kv-wrap{padding:20px}',
                'methods' => [
                    'SetHeader' => [
                        ['odd' => $pdfHeader, 'even' => $pdfHeader],
                    ],
                    'SetFooter' => [
                        ['odd' => $pdfFooter, 'even' => $pdfFooter],
                    ],
                ],
                'options' => [
                    'title' => $title,
                    'subject' => Lang::t('PDF export generated by {app}', ['app' => SystemSettings::getAppName()]),
                    'keywords' => Lang::t('Yii2, grid, export, pdf'),
                ],
                'contentBefore' => '',
                'contentAfter' => '',
            ],
        ];

        parent::init();

        $this->setPagerOptionClass('options', 'kt-datatable__pager-nav');
        $this->setPagerOptionClass('linkContainerOptions', 'page-item');
        $this->setPagerOptionClass('linkOptions', 'page-link kt-datatable__pager-link kt-datatable__pager-link-number');
        $this->setPagerOptionClass('disabledListItemSubTagOptions', 'page-link');
    }

    /**
     *
     */
    public function generateCreateButton()
    {
        $view = $this->getView();
        //create button
        $this->createButton['visible'] = ArrayHelper::getValue($this->createButton, 'visible', true);
        if ($this->createButton['visible']) {
            $create_button_url = ArrayHelper::getValue($this->createButton, 'url', Url::to(array_merge(['create'], Yii::$app->request->queryParams)));
            $create_button_label = ArrayHelper::getValue($this->createButton, 'label', '<i class="fa fa-plus-circle"></i> ' . Lang::t('Add ' . $view->context->resourceLabel));
            $create_button_html_options = ArrayHelper::getValue($this->createButton, 'options', ['class' => 'btn btn-brand btn-bold btn-upper btn-font-sm', 'data-pjax' => 0]);
            $create_button_modal = ArrayHelper::getValue($this->createButton, 'modal', false);
            if ($create_button_modal) {
                $create_button_html_options['data-toggle'] = 'modal';
                $create_button_html_options['data-href'] = $create_button_url;
                $create_button_html_options['data-grid'] = $this->id . '-pjax';
                $create_button_url = '#';
            }

            array_push($this->toolbarButtons, Html::a($create_button_label, $create_button_url, $create_button_html_options));
        }


    }


    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function run()
    {
        //do some stuff here
        parent::run();
    }

    /**
     * Registers client assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        //register custom assets here
        GridViewAsset::register($view);
        parent::registerAssets();
    }

    protected function setToolbar()
    {
        $this->generateCreateButton();
        $buttons = $this->toolbarButtons;
        if (($key = array_search('{export}', $this->toolbar)) !== false) {
            unset($this->toolbar[$key]);
        }
        if (($key = array_search('{toggleData}', $this->toolbar)) !== false) {
            unset($this->toolbar[$key]);
        }

        array_push($this->toolbar, '{toggleData}');

        array_push($this->toolbar, '{refreshButton}');

        if ($this->showExportButton) {
            array_push($this->toolbar, '{export}');
        }


        if (!empty($buttons)) {
            array_push($this->toolbar, [
                'content' => implode(' ', $buttons),
            ]);
        }

        //dd($this->toolbar);
    }

    /**
     * @return string
     */
    protected function generateRefreshButton()
    {
        $button = '';
        if ($this->showRefreshButton) {
            $template = '<div class="btn-group">{button}</div>';

            $button = strtr($template, [
                '{button}' => Html::a('<i class="fas fa-redo-alt"></i>', empty($this->refreshUrl) ? Yii::$app->getUrlManager()->createUrl(array_merge([Yii::$app->controller->route], Yii::$app->controller->actionParams)) : $this->refreshUrl, ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => Lang::t('Refresh Grid')]),
            ]);
        }

        return $button;
    }


    protected function setReplaceTags()
    {
        $this->replaceTags = [
            '{refreshButton}' => $this->generateRefreshButton(),
        ];
    }

    /**
     * Initializes and sets the grid panel layout based on the [[template]] and [[panel]] settings.
     * @throws InvalidConfigException
     */
    protected function initPanel()
    {
        if (!$this->bootstrap || !is_array($this->panel) || empty($this->panel)) {
            return;
        }
        $options = ArrayHelper::getValue($this->panel, 'options', []);
        $type = ArrayHelper::getValue($this->panel, 'type', 'default');
        $heading = ArrayHelper::getValue($this->panel, 'heading', '');
        $footer = ArrayHelper::getValue($this->panel, 'footer', '');
        $before = ArrayHelper::getValue($this->panel, 'before', '');
        $after = ArrayHelper::getValue($this->panel, 'after', '');
        $headingOptions = ArrayHelper::getValue($this->panel, 'headingOptions', []);
        $titleOptions = ArrayHelper::getValue($this->panel, 'titleOptions', []);
        $footerOptions = ArrayHelper::getValue($this->panel, 'footerOptions', []);
        $beforeOptions = ArrayHelper::getValue($this->panel, 'beforeOptions', []);
        $afterOptions = ArrayHelper::getValue($this->panel, 'afterOptions', []);
        $summaryOptions = ArrayHelper::getValue($this->panel, 'summaryOptions', []);
        $panelHeading = '';
        $panelBefore = '';
        $panelAfter = '';
        $panelFooter = '';
        $isBs4 = $this->isBs4();
        if (isset($this->panelPrefix)) {
            static::initCss($options, $this->panelPrefix . $type);
        } else {
            $this->addCssClass($options, self::BS_PANEL);
            Html::addCssClass($options, $isBs4 ? "border-{$type}" : "panel-{$type}");
        }
        static::initCss($summaryOptions, $this->getCssClass(self::BS_PULL_RIGHT));
        $titleTag = ArrayHelper::remove($titleOptions, 'tag', ($isBs4 ? 'h3' : 'h3'));
        static::initCss($titleOptions, $isBs4 ? 'm-0' : $this->getCssClass(self::BS_PANEL_TITLE));
        if ($heading !== false) {
            //$color = $isBs4 ? ($type === 'default' ? ' bg-light' : " text-white bg-{$type}") : '';
            //static::initCss($headingOptions, $this->getCssClass(self::BS_PANEL_HEADING) . $color);
            $panelHeading = Html::tag('div', $this->panelHeadingTemplate, $headingOptions);
        }
        if ($footer !== false) {
            static::initCss($footerOptions, $this->getCssClass(self::BS_PANEL_FOOTER));
            $content = strtr($this->panelFooterTemplate, ['{footer}' => $footer]);
            $panelFooter = Html::tag('div', $content, $footerOptions);
        }
        if ($before !== false) {
            static::initCss($beforeOptions, 'kv-panel-before');
            $content = strtr($this->panelBeforeTemplate, ['{before}' => $before]);
            $panelBefore = Html::tag('div', $content, $beforeOptions);
        }
        if ($after !== false) {
            static::initCss($afterOptions, 'kv-panel-after');
            $content = strtr($this->panelAfterTemplate, ['{after}' => $after]);
            $panelAfter = Html::tag('div', $content, $afterOptions);
        }
        $out = strtr($this->panelTemplate, [
            '{panelHeading}' => $panelHeading,
            '{type}' => $type,
            '{panelFooter}' => $panelFooter,
            '{panelBefore}' => $panelBefore,
            '{panelAfter}' => $panelAfter,
        ]);

        $out = $this->layout;

        $this->layout = Html::tag('div', strtr($out, [
            '{title}' => Html::tag($titleTag, $heading, $titleOptions),
            '{summary}' => Html::tag('div', '{summary}', $summaryOptions),
        ]), $options);
    }
}