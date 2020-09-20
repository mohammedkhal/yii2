<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use common\models\UsersFiles;
use kartik\form\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * User controller
 */
class UserController extends Controller
{
    

    public function behaviors()
    {
        return
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['update', 'delete', 'view', 'create', 'index'],
                    'rules'
                    => [
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'matchCallback' => function ($rule, $action) {
                                return Yii::$app->user->isGuest;
                            }
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update', 'delete', 'view', 'index'],
                            'matchCallback' => function ($rule, $action) {
                                return !Yii::$app->user->isGuest and !(Yii::$app->request->get('id') != Yii::$app->user->id);
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $users = new User;
        // var_dump($users->fetchAllActive());exit;
        $users = $users->fetchAllActive();
        return $this->render('index', [
            'users' => $users
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $user = $this->findModel($id);

        return $this->render('view', [
            'model' => $user,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        ini_set('memory_limit', '-1');

        $model = new User(['scenario' => 'register']);
        // validate any AJAX requests fired off by the form
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post()) {
            $checkLoad = $model->load(Yii::$app->request->post());
            $model->generateAccessToken();
            $model->files = UploadedFile::getInstances($model, 'files');
            if ($checkLoad && $model->save()) {
                return $this->redirect(['../site/login']);
            }
        }

        $model->password = "";
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing dUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // validate any AJAX requests fired off by the form
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->post()) {
            if (!empty(Yii::$app->request->post('User')['password'])) {
                $model->password = Yii::$app->request->post('User')['password'];
            }
            
            $filesIDs = Yii::$app->request->post('files_ids');

            $checkLoad = $model->load(Yii::$app->request->post());
            $file = UsersFiles::setDeleted($filesIDs, $id);

            // var_dump(UploadedFile::getInstances($model, 'files'));exit;
            $model->files = UploadedFile::getInstances($model, 'files');

            if ($checkLoad && $model->save()) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $model->password = "";
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionInactiveFile($id, $user_id)
    {
        return true;
    }


    /**
     * Deletes an existing dUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        $user->status = User::STATUS_DELETED;
        $user->update();

        return $this->redirect(['index']);
    }

    /**
     * Finds the dUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return dUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
