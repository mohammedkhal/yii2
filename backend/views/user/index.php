<?php

use common\models\User;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Userearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('Add Address', ['address/create'], ['class' => 'btn btn-success']) ?> </p>

    <?php $dataProvider = new ActiveDataProvider([
        'query' => User::find(),
        'pagination' => [
            'pageSize' => 20,
        ],
    ]); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'update',
    ]); ?>
</div>