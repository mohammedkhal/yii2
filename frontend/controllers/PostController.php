<?php

namespace frontend\controllers;

use common\models\Post as ModelsPost;
use frontend\resource\Post;
use Yii;
use frontend\controllers\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class PostController extends ActiveController
{
    public $modelClass = Post::class;


    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',

    ];


    public function actionPostView($id)
    {
        $postID = Yii::$app->request->get('id');

        $post = $this->findModel($postID);

        return $post;
    }


    /**
     * Finds the dUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return dUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = $this->modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
