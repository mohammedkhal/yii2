<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\user */
$this->title = 'Update Address';
$this->params['breadcrumbs'][] = ['label' => 'users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_update_address', [
        'model' => $model,
    ]) ?>

</div>