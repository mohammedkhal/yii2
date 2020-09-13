<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap\Modal;

$params = Yii::$app->request->get();
?>
 
<?php
// echo DetailView::widget($settings); // refer the demo page for widget settings

Modal::begin([
    'header' => '<h3>Add Location</h3>',
    'id' => 'modal',
    'size' => 'modal-lg'
]);
echo "<div id = 'modalContent'></div>";
Modal::end();
?>

<?php
$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    'id',
    'phone_number',
    'first_name',
    'last_name',
    [
        'attribute' => 'status',
        'filter' => ['Active' => 'active', 'Inactive' => 'inactive', 'Delete' => 'delete'],
    ],

    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}{update}{delete}',
        'header' => 'Action',
        'visible' => true,
        'buttons' => ['delete' => function ($url, $model) {
            return $model->status == 'deleted' ? false : Html::a('<span class=" glyphicon glyphicon-remove-circle"></span>', $url);
        }],
        'options' => ['width' => '70']
    ],
];
?>

<?php

$x = ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'dropdownOptions' => [
        'label' => 'Export All',
        'class' => 'btn btn-outline-secondary'
    ]
]);
echo
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'export' => false,
        'toolbar' => [
            $x
        ],
        'panel' => [
            'type' => 'primary',
        ]
    ]);
?>
