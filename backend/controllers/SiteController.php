<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use kartik\form\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index', 'details'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
        $users = User::find();

        // get the total number of articles (but do not fetch the article data yet)
        $count = $users->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count]);

        // limit the query using the pagination and retrieve the articles
        $users = $users->offset($pagination->offset)
            ->limit(10)
            ->all();

        return $this->render('index', [
            'users' => $users,
            'pagination' => $pagination,

        ]);
    }

    public function actionDetails($id)
    {
        $userDetails = User::Details($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $userDetails,

        ]);
        //  var_dump($dataProvider->getModels());exit;
        return $this->render('detail', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        // validate any AJAX requests fired off by the form
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }


        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('index');
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {

        Yii::$app->user->logout();
        // var_dump(Yii::$app->user->isGuest);exit;

        return $this->goHome();
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
