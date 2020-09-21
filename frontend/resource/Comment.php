<?php

namespace frontend\resource;

/**
 * Password reset request form
 */
class Comment extends \common\models\Comment
{
    public function fields()
    {
        return ['post_id', 'description'];
    }

    public function extraFields()
    {
        return ['post', 'updated_at', 'created_at'];
    }

    /*** Gets query  for [[Post]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PostQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
