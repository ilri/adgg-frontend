<?php

namespace backend\modules\core\models;

use backend\modules\auth\models\Users;
use backend\modules\auth\Session;
use common\helpers\DateUtils;
use common\helpers\FileManager;
use common\models\ActiveRecord;
use common\models\ActiveSearchInterface;
use common\models\ActiveSearchTrait;
use Yii;

/**
 * This is the model class for table "member_registration_document".
 *
 * @property int $id
 * @property int $org_id
 * @property string $document_no
 * @property int $doc_type_id
 * @property string $description
 * @property string $file_name
 * @property int $is_active
 * @property int $is_approved
 * @property string $start_date
 * @property string $renewal_date
 * @property string $created_at
 * @property int $created_by
 * @property int $approved_by
 * @property string $approved_at
 * @property string $date_approved
 * @property string $approval_notes
 *
 * @property Organization $org
 * @property RegistrationDocumentType $docType
 * @property Users $approvedBy
 */
class RegistrationDocument extends ActiveRecord implements ActiveSearchInterface
{
    use OrganizationDataTrait, ActiveSearchTrait;

    const SCENARIO_APPROVE = 'approve';

    /**
     * @var string
     */
    public $tmp_file_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%org_registration_document}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['tmp_file_name', 'required', 'when' => function (self $model) {
                return $model->isNewRecord;
            }],
            [['org_id', 'doc_type_id'], 'required'],
            [['org_id', 'doc_type_id', 'is_active', 'is_approved'], 'integer'],
            [['start_date', 'renewal_date'], 'safe'],
            [['document_no'], 'string', 'max' => 128],
            [['description', 'file_name'], 'string', 'max' => 255],
            [['date_approved', 'approval_notes'], 'required', 'on' => self::SCENARIO_APPROVE],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['org_id' => 'id']],
            [['doc_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistrationDocumentType::class, 'targetAttribute' => ['doc_type_id' => 'id']],
            [[self::SEARCH_FIELD], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'org_id' => 'Org ID',
            'document_no' => 'Document Number',
            'doc_type_id' => 'Document Type',
            'description' => 'Description',
            'file_name' => 'Document',
            'is_active' => 'Active',
            'is_approved' => 'Approved',
            'start_date' => 'Start Date',
            'renewal_date' => 'Renewal Date',
            'tmp_file_name' => 'Document',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'date_approved' => 'Date Approved',
            'approved_at' => 'Approved At',
            'approved_by' => 'Approved By',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocType()
    {
        return $this->hasOne(RegistrationDocumentType::class, ['id' => 'doc_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'approved_by']);
    }

    /**
     * {@inheritDoc}
     */
    public function searchParams()
    {
        return [
            ['document_no', 'document_no'],
            'doc_type_id',
            'org_id',
            'is_active',
            'is_approved',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setFileName();

            if ($this->scenario === self::SCENARIO_APPROVE) {
                $this->approve();
            }

            return true;
        }
        return false;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        FileManager::deleteDirOrFile($this->getFilePath());
    }

    protected function approve()
    {
        $this->is_approved = 1;
        $this->approved_at = DateUtils::mysqlTimestamp();
        $this->approved_by = Session::userId();
    }


    /**
     *
     * @return string
     */
    public function getDir()
    {
        return FileManager::createDir($this->org->getDir() . DIRECTORY_SEPARATOR . 'documents');
    }

    protected function setFileName()
    {
        if (empty($this->tmp_file_name))
            return false;

        $ext = $ext = pathinfo($this->tmp_file_name, PATHINFO_EXTENSION);
        $file_name = microtime(true) . '.' . $ext;
        $temp_dir = dirname($this->tmp_file_name);
        $new_path = $this->getDir() . DIRECTORY_SEPARATOR . $file_name;
        if (copy($this->tmp_file_name, $new_path)) {
            $this->file_name = $file_name;
            $this->tmp_file_name = null;

            if (!empty($temp_dir)) {
                FileManager::deleteDirOrFile($temp_dir);
            }
        }
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getFileUrl()
    {
        $file_path = $this->getFilePath();
        if (empty($file_path)) {
            return null;
        }
        $asset = Yii::$app->getAssetManager()->publish($file_path);

        return $asset[1];
    }

    /**
     * @return null|string
     */
    public function getFilePath()
    {
        $path = null;
        if (empty($this->file_name))
            return null;

        $file = $this->getDir() . DIRECTORY_SEPARATOR . $this->file_name;
        if (file_exists($file)) {
            $path = $file;
        }

        return $path;
    }

    /**
     * @return bool
     */
    public function canBeApproved(): bool
    {
        if ($this->is_approved) {
            return false;
        }
        return true;
    }
}
