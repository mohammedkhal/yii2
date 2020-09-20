<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

?>
  <?= GridView::widget([

        'dataProvider' => $dataProvider,
        'panel' => ['type' => 'primary', 'heading' => 'User Details @-@'],

        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute' => 'user_country',

                'group' => true,  // enable grouping
            ],
            [
                'attribute' => 'first_name',

                'group' => true,
                'subGroupOf' => 1
            ],
            [
                'attribute' => 'user_file',

                'format' => 'html',

                'label' => 'Image',

                'value' => function ($data) {
                    return Html::img('/' . $data['user_file'], ['width' => 159, 'height' => 100]);
                },

            ],




        ],


    ]); ?>
