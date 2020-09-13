<?php

use kartik\file\FileInput;
use kartik\password\PasswordInput;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$allimage = array();
$imagesListId = array();
$config = array();

foreach ($model->allFilesNotDeleted as $filee) {
    $imagesListId[]['key'] = $filee->id;
    $filePath = Yii::$app->homeUrl . $filee->file;
    $allimage[] = $filePath;
    $fileSize = filesize($filee->file);
    if (strchr($filee->file, '.') == '.pdf') {
        $temp = ['caption' => "file", 'type' => 'pdf', 'downloadUrl' => '/admin/download', 'size' => $fileSize, 'width' => "120px", 'key' => $filee->id];
    } else {
        $temp = ['caption' => "file", 'type' => 'image', 'downloadUrl' => '/admin/download', 'size' => $fileSize, 'width' => "120px", 'key' => $filee->id];
    }
    array_push($config, $temp);
}

if (!$model->address) {
    echo Html::button('Add Address', ['value' => Url::to('/address/create'), 'class' => 'btn btn-success', 'id' => 'modalButton']);
} else {
    echo Html::button('Update Address', ['value' => Url::to('/address/update?id=' . $model->address->id), 'class' => 'btn btn-primary', 'id' => 'updateButton']);
    echo Html::button('View Address', ['value' => Url::to('/address/view?id=' . $model->id), 'class' => 'btn btn-success', 'id' => 'viewButton']);

}


Modal::begin([
    'header' => '<h3>Add Location</h3>',
    'id' => 'modal',
    'size' => 'modal-lg'
]);
echo "<div id = 'modalContent'></div>";
Modal::end(); 

Modal::begin([
    'header' => '<h3>View Location</h3>',
    'id' => 'viewModal',
    'size' => 'modal-lg'
]);
echo "<div id = 'viewContent'></div>";
Modal::end();

Modal::begin([
    'header' => '<h3>Update Location</h3>',
    'id' => 'updateModal',
    'size' => 'modal-lg'
]);
echo "<div id = 'updateContent'></div>";
Modal::end();


$form = ActiveForm::begin([
    'action' => ['update', 'id' => $model->id],
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
    'enableClientValidation' => false,
    'enableAjaxValidation' => false,
]) ?>

<?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'password')->widget(PasswordInput::classname(), []) ?>
<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>


<?= $form->field($model, 'files[]')->widget(FileInput::classname(), [
    'options' => [
        'multiple' => true,
        'id' => 'input-702',

    ],
    'pluginOptions' => [
        'otherActionButtons' => '<input id = {key} name = "files_ids[]" type="hidden" value = {key} ></input>',
        'allowedFileExtensions' => ['jpg', 'png', 'pdf'],
        'browseOnZoneClick' => true,
        'showRemove' => false,
        'showUpload' => false,
        'initialPreview' => $allimage,
        'overwriteInitial' => false,
        'initialPreviewAsData' => true,
        'fileActionSettings' => [
            'showZoom' => true,
            'showDelete' => true,
        ],
        'deleteUrl' => "/admin/inactive-file",

        'initialPreviewConfig' => $config,
    ]
]);
?>


<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
