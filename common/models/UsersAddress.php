<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\Expression;
use Yii;

/**
 * This is the model class for table "users_address".
 *
 * @property int $id
 * @property string $country
 * @property string $city
 * @property int $resident
 * @property string|null $national_number
 * @property string|null $passport_number
 * @property string|null $avatar
 * @property string $status
 * @property string $updated_at
 * @property string|null $created_at
 */
class UsersAddress extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 'deleted';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';

    public $file;

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_AFTER_INSERT, [$this, 'uploadFile']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'uploadFile']);
    }

    public function afterValidate()
    {
        $userID = Yii::$app->user->id;

        if ($this->hasErrors()) {
            $errors = $this->getErrors();

            foreach ($errors as $error => $oneError) {
                $log = new UsersValidationLog;
                $log->validation_error = $oneError[0];
                $log->user_id = $userID;
                if (!$log->save()) {
                    return false;
                }
            }
        }

        return true;
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    DbActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    DbActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        // var_dump($this->country  );exit;
        return [
            [['country', 'city'], 'required'],
            [['resident'], 'integer'],
            ['national_number', 'required', 'when' => function ($model) {
                return $this->resident == 1;
            }, 'enableClientValidation' => false],
            [['file'], 'file', 'maxSize' => 1024 * 1024 * 2, 'extensions' => 'jpg, png', 'checkExtensionByMimeType' => false],
            [['updated_at', 'created_at'], 'safe'],
            [['country', 'city', 'avatar'], 'string', 'max' => 255],
            [['national_number','passport_number', 'status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'city' => 'City',
            'resident' => 'Residentancy',
            'national_number' => 'National Number',
            'passport_number' => 'Passport Number',
            'avatar' => 'Avatar',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function uploadFile()
    {
        if (!isset($this->file)) {
            return false;
        }

        $fileUpload = $this->file->saveAs('uploads/' . time() . '.' . $this->file->extension);
        if ($fileUpload) {
            $this->avatar = 'uploads/' . time() . '.' . $this->file->extension;
            if (!$this->save()) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }
}
