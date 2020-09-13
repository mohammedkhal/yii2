<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$value = "false";
$form = ActiveForm::begin([
    'id' => 'insert_address',
    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'validateOnChange' => false,

]) ?>
<?= $form->field($model, 'country')->textInput(['maxlength' => 255, 'enableAjaxValidation' => false]) ?>
<?= $form->field($model, 'city')->textInput(['maxlength' => 255,'enableAjaxValidation' => false]) ?>
<?= $form->field($model, 'resident')->dropDownList(
    [1 => 'I am Resident',0 => 'I am not Resident', 'enableClientValidation' => true]
); ?>
<?= $form->field($model, 'national_number')->textInput(['maxlength' => 10]) ?>
<?= $form->field($model, 'passport_number')->textInput(['maxlength' => 10]) ?>

<?= $form->field($model, 'avatar')->widget(FileInput::classname(), [
    'options' => [
        'multiple' => false,
        'id' => 'input-7d',

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
        <?= Html::submitButton('Add Address', ['class' => 'btn btn-primary' , 'onclick' => 'updateHiddenInput()']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>