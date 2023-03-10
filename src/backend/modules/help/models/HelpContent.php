<?php

namespace backend\modules\help\models;

use backend\modules\auth\models\UserLevels;
use backend\modules\auth\Session;
use backend\modules\conf\settings\SystemSettings;
use backend\modules\help\Help;
use common\helpers\DateUtils;
use common\helpers\Lang;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use kartik\mpdf\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style;

/**
 * This is the model class for table "help_content".
 *
 * @property integer $id
 * @property integer $module_id
 * @property integer $user_level_id
 * @property string $name
 * @property string $slug
 * @property string $content
 * @property string $permissions
 * @property string $tags
 * @property string $secondary_permissions
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $is_active
 * @property  integer $is_for_android
 *
 * @property HelpModules $module
 * @property UserLevels $userLevel
 */
class HelpContent extends ActiveRecord implements ActiveSearchInterface
{
    use ActiveSearchTrait;

    public $enableAuditTrail = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%help_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'name', 'content'], 'required'],
            [['module_id', 'user_level_id', 'is_active', 'is_for_android'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 128],
            ['name', 'unique'],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_id' => 'Module',
            'user_level_id' => 'User Level',
            'name' => 'Help Topic',
            'slug' => 'Slug',
            'content' => 'Content',
            'permissions' => 'Permissions',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'is_active' => 'Active',
            'is_for_android' => 'Is For Android',
        ];
    }

    /**
     * @inheritdoc
     */
    public function searchParams()
    {
        return [
            'id',
            'name',
            'module_id',
            'user_level_id',
            'is_for_android'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(HelpModules::class, ['id' => 'module_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLevel()
    {
        return $this->hasOne(UserLevels::class, ['id' => 'user_level_id']);
    }

    /**
     * Get permissions from a key based array
     * @param $values
     * @return mixed
     */
    public static function getPermissions($values)
    {
        // if the values are an array, like [1, 2, 4], we loop over
        // the array, and get the values by key from the help array
        if (is_array($values)) {
            $names = '';
            foreach ($values as $value) {
                $names .= Help::$permissions[$value] . ', ';
            }
            // remove last comas, and last whitespace if any
            return rtrim(str_replace_last(',', '', $names));
        }
        // if the values were a plain string, we attempt to cast to an integer and
        // then fetch by key
        if (is_string($values)) {
            return Help::$permissions[(int)$values];
        }
        return $values;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->slug = str_slug($this->name);
        // these dummy tags will allow us to find partial search results
        $this->tags = self::getPermissions($this->permissions);
        if (empty($this->user_level_id)) {
            $this->user_level_id = UserLevels::getScalar('id', ['id' => $this->user_level_id]);
        } else {
            $this->user_level_id = UserLevels::getScalar('id', ['id' => $this->user_level_id]);
        }
        /* if (!empty($this->user_level_id)) {
             if (is_string($this->user_level_id)) {
                 $this->user_level_id = array_map('trim', explode(' ', $this->user_level_id));
             }
         } else {
             $this->user_level_id = [];
         }*/
        // just fill all permissions by default
        $this->permissions = json_encode(array_keys(Help::$permissions));
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->permissions = json_decode($this->permissions);
        $this->user_level_id = UserLevels::getScalar('id', ['id' => $this->user_level_id]);
        parent::afterFind();
    }

    public static function exportPdf($content, $options = [])
    {
        $file_name = 'ADGG Help Content';
        $destination = Pdf::DEST_BROWSER;
        $paperSize = 'A4';
        $title = 'ADGG Help Content';
        $pdfHeader = [
            'L' => [
                //'content' => $model->org->name,
                'content' => SystemSettings::getAppName(),
                'font-size' => 8,
                'color' => '#333333',
            ],
            'C' => [
                'content' => $title,
                //'content' => '',
                'font-size' => 16,
                'color' => '#333333',
            ],
            'R' => [
                'content' => '',
                'font-size' => 8,
                'color' => '#333333',
            ],
        ];
        $footer = [
            'L' => [
                'content' => '',
                'font-size' => 8,
                'color' => '#333333'
            ],
            'C' => [
                'content' => Lang::t('Generated') . ': ' . DateUtils::formatToLocalDate(date(time()), "D, d-M-Y g:i a"),
                'font-size' => 8,
                'color' => '#333333'
            ],
            'R' => [
                'content' => '',
                'font-size' => 8,
                'color' => '#333333'
            ],
        ];

        $config = [
            'mode' => 'UTF-8',
            'format' => 'A4-L',
            'destination' => 'D',
            'marginTop' => 15,
            'marginBottom' => 0,
            'cssInline' =>
                '.table {margin-bottom:5px;}' .
                'p,th,td{font-size:11px!important;line-height: normal!important;}' .
                'th,td {border:none!important;padding: 2px!important;line-height: 1!important;}' .
                'hr {margin-top:2px;margin-bottom:2px;}' .
                'h1,h3 {line-height:normal;margin:30 0 30 0;}' .
                'h1,h3,h2,h4,h5 {font-weight:bold;}' .
                'h1 {font-size: 18px;}' .
                'h3 {font-size: 14px;}' .
                'p {margin-bottom:5px;}' .
                'img {width:1024!important;}',
            'methods' => [
                'SetHeader' => [
                    ['odd' => $pdfHeader, 'even' => $pdfHeader],
                ],
                'SetFooter' => [
                    ['odd' => $footer, 'even' => $footer],
                ],
            ],
            'options' => [
                'title' => $title,
            ],
        ];

        $html = $content;

        $config['filename'] = "{$file_name}.pdf";
        $config['methods']['SetAuthor'] = [SystemSettings::getAppName()];
        $config['methods']['SetCreator'] = [Session::getName()];
        $config['options']['CSSselectMedia'] = 'mpdf';
        $config['content'] = $html;
        //$config['format'] = [100, 300];
        $config['format'] = $paperSize;
        $config['destination'] = $destination;
        $config['options']['img_dpi'] = 92;

        $pdf = new Pdf($config);
        return $pdf->render();

    }

    #export user manual as a word document

    public static function exportWord($content, $options = []){
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Style::addTitleStyle(1, array('size' => 14));
        $section->addTitle("ADGG USER MANUAL");
        $html = $content;
        Html::addHtml($section, $html, false, false);

        // Saving the document as OOXML file...
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $temp_file_uri = tempnam('', 'xyz');
        $objWriter->save($temp_file_uri);
        //download code
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=ADGG USER MANUAL.docx');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Content-Length: ' . filesize($temp_file_uri));
        readfile($temp_file_uri);
        unlink($temp_file_uri); // deletes the temporary file
        exit;

    }

}
