<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $user app\users\User */
$this->title = $user->first_name . " " . $user->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>

        <?= Html::a('Delete', ['address/delete', 'id' => $user_address->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
  
    <?= 
    
    DetailView::widget([
        'model' => $user_address,
        'attributes' => [
            [
                'attribute' => 'avatar',
                'value' => function ($user_address) {
                     return Html::img(Yii::$app->homeUrl . $user_address->avatar, ['alt' => 'yii', 'height' => 100]);
                },
                'format' => 'raw',
            ],
            'country',
            'city',
            'national_number',
            'passport_number',

        ],
    ]) ?>

</div>
