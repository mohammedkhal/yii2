<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Login form
 */
class SearchModel extends Model
{
    public $id;
    public $password;
    public $phone_number;
    public $first_name;
    public $last_name;
    public $status;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'phone_number', 'first_name', 'last_name', 'status'], 'string', 'max' => 255],
            [['id'], 'integer']
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider =  new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $this->load($params);

        $query->andFilterWhere(['id' => $this->id]);

        $query->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
