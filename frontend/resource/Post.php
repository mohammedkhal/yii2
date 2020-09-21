<?php

namespace frontend\resource;

/**
 * Password reset request form
 */
class Post extends \common\models\Post
{
    
    public function fields()
    {
        return ['id', 'title', 'description'];
    }

    public function extraFields()
    {
        return ['comments', 'extraData', 'updated_at', 'created_at'];
    }

    public function getExtraData()
    {
        return ['title' => $this->title, 'status' => $this->status, 'updated_at' => $this->updated_at];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CommentQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }
}
