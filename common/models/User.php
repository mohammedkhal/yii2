<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord as DbActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $phone_number
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_DELETED = 'deleted';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';

    public $files;
    public $user_file;
    public $user_country;

    public function init()
    {
        parent::init();

        // $this->on(self::EVENT_AFTER_INSERT, [$this, 'uploadFile']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'uploadFile']);
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'afterValidate']);
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
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name'], 'string', 'length' => 4],
            [['phone_number', 'first_name', 'last_name'], 'required'],
            [['password'], 'required', 'on' => 'register'],
            [['updated_at', 'created_at', 'password', 'files'], 'safe'],
            [['password', 'first_name', 'last_name', 'status', 'avatar'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 10],
            [['phone_number'], 'unique'],
            [['files'], 'file', 'maxSize' => 1024 * 1024 * 2, 'extensions' => 'jpg, pdf, png', 'checkExtensionByMimeType' => true, 'maxFiles' => 4],
            [['password'], 'setPassword', 'when' => function ($model) {
                return !empty($this->password);
            }],
            ['password', 'default', 'value' => function () {
                return $this->findOne($this->id)->password;
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone_number' => 'Phone Number',
            'password' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'status' => 'Status',
            'files' => 'Avatar',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        return $this->password = Yii::$app->security->generatePasswordHash($this->password);
    }

    /**
     * Generates access_token
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }   

    /**
     * {@inheritdoc}
     */
    public function uploadFile()
    {
        if(!$this->isNewRecord){
            $file = UsersFiles::updateAll(['status' => UsersFiles::STATUS_DELETED], ['and', ['=', 'user_id', $this->id], ['=', 'status', UsersFiles::STATUS_INACTIVE]]);
        }

        if (!count($this->files)) {
            return false;
        }

        foreach ($this->files as $file) {
            $rand = time() . rand(1, 9);
            $userFile = new UsersFiles();
            $fileUpload = $file->saveAs('uploads/' . $rand . '.' . $file->extension);
            if ($fileUpload) {
                $userFile->user_id = $this->id;
                $userFile->file = 'uploads/' . $rand . '.' . $file->extension;
                if (!$userFile->save()) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    /**
     * Finds user by phone_number
     *
     * @param string $phone_number
     * @return static|null
     */
    public static function findByPhoneNumber($phone_number)
    {
        return static::findOne(['phone_number' => $phone_number]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAllActive()
    {
        $query = new \yii\db\Query();

        $users = User::find([
            'status' => User::STATUS_ACTIVE,
        ]);

        return $users;
    }
    
 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllFilesNotDeleted()
    {
        return $this->hasMany(UsersFiles::className(), ['user_id' => 'id'])->where(['!=', 'status', UsersFiles::STATUS_DELETED]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOneImage()
    {
        $file =  $this->hasMany(UsersFiles::className(), ['user_id' => 'id'])->where(['status' => UsersFiles::STATUS_ACTIVE])->one();
        if (isset($file) && strchr($file->file, '.') == '.pdf') {
            return '/uploads/defult.jpeg';
        }
        return isset($file) ? '/'.$file->file : '/uploads/defult.jpeg';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(UsersAddress::className(), ['user_id' => 'id'])->from(UsersAddress::tableName());
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllFiles()
    {
        return $this->hasMany(UsersFiles::className(), ['user_id' => 'id'])->from(UsersFiles::tableName());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function Details($id)
    {

        $users = User::find()->select('users.first_name, users_address.country  as user_country, users_files.file  as user_file')
        ->joinWith('address', true, 'LEFT JOIN')
        ->joinWith('allFiles', true, 'RIGHT JOIN')
        ->where(['users.id' => $id]);

        // print_r($users->createCommand()->getRawSql());exit;
        return $users;
    }
}
