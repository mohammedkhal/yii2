<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "users_validation_log".
 *
 * @property int $id
 * @property int $user_id
 * @property int $validation_error
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class UsersValidationLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_validation_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'validation_error'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'validation_error' => 'Validation Error',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
