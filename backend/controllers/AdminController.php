<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\AdminLoginForm;
use common\models\SearchModel;
use common\models\User;
use common\models\UsersFiles;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['update', 'delete', 'view', 'login', 'index', 'users', 'download'],
                    'rules'
                    => [
                        [
                            'allow' => true,
                            'actions' => ['login'],
                            'matchCallback' => function ($rule, $action) {

                                return Yii::$app->admin->isGuest;
                            }
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update', 'delete', 'view', 'index', 'users', 'download'],
                            'matchCallback' => function ($rule, $action) {
                                return !Yii::$app->admin->isGuest;
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
    public function actionHome()
    {
        return $this->render('home');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->admin->isGuest) {
            return $this->goHome();
        }

        $model = new AdminLoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->redirect('search');
        } else {

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionUsers()
    {
        $models = User::fetchAllActive();

        $dataProvider = new ActiveDataProvider([
            'query' => $models,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionSearch()
    {

        $searchModel = new SearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // var_dump(Yii::getAlias('@backend'));exit;
        $user = $this->findModel($id);

        return $this->render('view', [
            'model' => $user,
        ]);
    }
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {

        Yii::$app->admin->logout();
        // var_dump(Yii::$app->admin->isGuest);exit;

        return $this->goHome();
    }


    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionFiles($id)
    {
        $file = UsersFiles::findOne($id);


        return Yii::$app->response->sendFile($file->file, $file->file, ['inline' => true]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionDownload()
    {
        $fileID = Yii::$app->request->post();
        var_dump($fileID);
        exit;

        // $userFile = UsersFiles::findOne($fileID);
        // $file = $userFile->file;
        // $size = filesize($file);
        // header('Content-Type: application/octet-stream');
        // header('Content-Length: ' . $size);
        // header('Content-Disposition: attachment; filename=' . $file);
        // header('Content-Transfer-Encoding: binary');

        // if (file_exists($file)) {
        //     return Yii::$app->response->sendFile($file);
        // } else {
        //     throw new \yii\web\NotFoundHttpException("{$file} is not found!");
        // }
        return false;
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
        if (Yii::$app->request->post()) {

            $requestPassword = Yii::$app->request->post('User')['password'];

            if (!empty($requestPassword)) {
                $model->password = $requestPassword;
            }
            $filesIDs = Yii::$app->request->post('files_ids');

            $checkLoad = $model->load(Yii::$app->request->post());
            $file = UsersFiles::setDeleted($filesIDs, $id);
            $model->files = UploadedFile::getInstances($model, 'files');

            if ($checkLoad && $model->save()) {
                return $this->render('../user/view', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->password = "";
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionInactiveFile()
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

        return $this->redirect(['admin/users']);
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
