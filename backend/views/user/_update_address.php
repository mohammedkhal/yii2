<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
// var_dump($model);exit;
$form = ActiveForm::begin([
    'id' => 'update_address',
    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'enableAjaxValidation' => true,
    'validateOnChange' => false,

]) ?>
<?= $form->field($model, 'country')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'city')->textInput(['maxlength' => 255]) ?>
<?= $form->field($model, 'resident')->dropDownList(
    [1 => 'I am Resident',0 => 'I am not Resident', 'enableClientValidation' => false]
); ?>
<?= $form->field($model, 'national_number')->textInput(['maxlength' => 10]) ?>
<?= $form->field($model, 'passport_number')->textInput(['maxlength' => 10]) ?>

<?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
    'options' => [
        'multiple' => false,
        'id' => 'input-8d',

    ],
    'pluginOptions' => [
        'allowedFileExtensions' => ['jpg', 'png'],

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
        <?= Html::submitButton('Update Address', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>