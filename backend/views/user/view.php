<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->first_name . " " . $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>

        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
  
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'avatar',
                'value' => function ($model) {
                    foreach ($model->allFilesNotDeleted as $filee) {
                        // var_dump(Yii::$app->homeUrl . $filee->file);exit;
                        if (substr($filee->file, 20) == 'pdf') {
                            echo Html::a(Html::img('/uploads/pdf-file.png', ['alt' => 'yii', 'height' => 100]), ['../admin/files', 'id' => $filee->id]);
                        } else
                            echo Html::a(Html::img(Yii::$app->homeUrl . $filee->file, ['alt' => 'yii', 'height' => 100]), ['../admin/files', 'id' => $filee->id]);
                    }
                },
                'format' => 'raw',
            ],
            'id',
            'first_name',
            'last_name',
            'phone_number',
            'created_at',

        ],
    ]) ?>

</div>
