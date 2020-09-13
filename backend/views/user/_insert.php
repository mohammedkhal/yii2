<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\password\PasswordInput;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
    
]) ?>
    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => 10, 'type' =>'number'])?>
    <?= $form->field($model, 'password')->widget(PasswordInput::classname(), [])?>
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'files[]')->widget(FileInput::classname(), [
    'options' => [
        'multiple' => true,
        'id' => 'input-702',

    ],
    'pluginOptions' => [

        'showRemove' => false,
        'showUpload' => false,
        'overwriteInitial' => false,

        'fileActionSettings' => [
            'showZoom' => true,
            'showDelete' => true,
        ],
    ]
]);
?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Sign Up', ['class'=>'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>