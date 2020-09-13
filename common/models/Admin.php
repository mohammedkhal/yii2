<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admins".
 *
 * @property int $id
 * @property string $phone_number
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string|null $ip_address
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 */
class Admin extends \yii\db\ActiveRecord  implements \yii\web\IdentityInterface
{
    const STATUS_DELETED = 'deleted';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admins';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_number', 'password', 'first_name', 'last_name'], 'required'],
            [['updated_at', 'created_at'], 'safe'],
            [['phone_number', 'password', 'first_name', 'last_name', 'status'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 15],
            [['phone_number'], 'unique'],
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
            'ip_address' => 'Ip Address',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
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
        return null;
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
}
