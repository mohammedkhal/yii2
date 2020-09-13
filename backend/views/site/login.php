<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                 'id' => 'login-form', 'enableClientValidation' => false,
                'enableAjaxValidation' => false
            ]); ?>

            <?= $form->field($model, 'phone_number')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <?= Html::a('Sign Up', ['..\user\create'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Or you Can Sign in As Admin', ['..\admin\login'], ['class' => 'btn btn-success']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>