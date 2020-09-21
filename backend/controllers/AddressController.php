<?php

namespace backend\controllers;

use common\models\User;
use common\models\UsersAddress;
use Yii;
use backend\Repositories\UserRepository;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class AddressController extends Controller
{
    protected $userRepository;

    public function __construct($id, $module, $config = [], UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct($id, $module, $config);
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules'
                    => [
                        [
                            'allow' => true,
                            'actions' => [''],
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->admin->isGuest;
                            }
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create', 'update', 'view', 'delete'],
                            'matchCallback' => function ($rule, $action) {
                                return !Yii::$app->admin->isGuest || !Yii::$app->user->isGuest;
                            }
                        ],
                    ],
                ],
            ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionCreate()
    {
        // var_dump($this->userRepository->fetchActiveUser());
        // exit;
        $model = new UsersAddress();

        $userID = Yii::$app->user->id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if (Yii::$app->request->post()) {
            $checkLoad = $model->load(Yii::$app->request->post());

            $model->file = UploadedFile::getInstance($model, 'avatar');
            $model->user_id = $userID;

            if ($checkLoad && $model->save()) {
                return $this->redirect(['/user/update', 'id' => $userID]);
            } else {
                return $this->renderAjax('/user/create-address', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->renderAjax('/user/create-address', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = UsersAddress::findOne($id);

        $userID = Yii::$app->user->id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if (Yii::$app->request->post()) {
            $checkLoad = $model->load(Yii::$app->request->post());

            $model->file = UploadedFile::getInstance($model, 'avatar');

            if ($checkLoad && $model->save()) {
                return $this->redirect(['/user/update', 'id' => $userID]);
            } else {
                return $this->renderAjax('/user/update-address', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->renderAjax('/user/update-address', [
                'model' => $model,
            ]);
        }
    }

    public function actionView($id)
    {
        $user = $this->findModel($id);
        $userAddress = $user->address;

        return $this->renderAjax('/user/view-address', [
            'user_address' => $userAddress,
            'user' => $user,
        ]);
    }

    public function actionDelete($id)
    {
        $userID = Yii::$app->user->id;
        $userAddress = UsersAddress::findOne($id);
        $userAddress->status = User::STATUS_DELETED;
        if ($userAddress->update()) {
            return $this->redirect(['../user/view', 'id' => $userID]);
        }

        return false;
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
