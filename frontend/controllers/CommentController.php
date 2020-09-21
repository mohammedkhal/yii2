<?php

namespace frontend\controllers;

use frontend\resource\Comment;
use yii\data\ActiveDataProvider;
use frontend\controllers\ActiveController;

/**
 * Site controller
 */
class CommentController extends ActiveController
{
    public $modelClass = Comment::class;

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
        'perPageHeader' => 'Tamimi',

    ];

    public function actions()
    {
        $actions = parent::actions();
    
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
    
        return $actions;
    }

    public function prepareDataProvider()
    {
        $activeData = new ActiveDataProvider([
            'query' => $this->modelClass::find(),
            'pagination' => [
                'pageSize' => 4,
            ],
        ]);
        return $activeData;
    }
}
