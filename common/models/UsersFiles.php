<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "users_files".
 *
 * @property int $id
 * @property int $user_id
 * @property string $file
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class UsersFiles extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 'deleted';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
        return 'users_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id',], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at', 'file'], 'safe'],
            [['file'], 'string', 'max' => 255],
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
            'file' => 'File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    function setInactive($id)
    {
        $file = UsersFiles::findOne($id);
        $file->status = self::STATUS_INACTIVE;

        return $file->update();
    }


    function setDeleted($filesIDs, $id)
    {
        
        if (null == $filesIDs) {
            $filesIDs = [];
        }
        $filesIDs = array_diff($filesIDs, ["false"]);

        $file = UsersFiles::updateAll(['status' => UsersFiles::STATUS_DELETED], ['and', ['=', 'user_id', $id], ['not in', 'id', $filesIDs]]);
        
        return $file;
    }

    public function removeDeletesFiles()
    {
        $files = static::findAll(['status' => self::STATUS_DELETED]);
        
       
        if($files){
            echo 'find all deleted image'.PHP_EOL;
        foreach ($files as $file) {
           
            if (file_exists(\Yii::getAlias('@backend-web').'/'. $file->file)) {
                unlink(\Yii::getAlias('@backend-web').'/'. $file->file);
                \Yii::$app->db->createCommand("delete from users_files   WHERE id = " . $file->id)->execute();
                echo 'delete image name = '.$file->file.PHP_EOL;
            }
        }
        echo 'delete All images '.PHP_EOL;
    }else{
        echo 'NO image for delete'. date('Y-m-d').PHP_EOL;
    }
      
        return true;

    }
}
