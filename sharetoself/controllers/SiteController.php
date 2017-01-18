<?php

namespace app\controllers;

use app\models\Subscribers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    private $LANDING = true;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        if($this->LANDING) {
            $this->layout = "clean";
            $model = new Subscribers();
            $string = Yii::$app->request->post('email');
            $error = '';

            if (!is_null($string)) {
                $model->email = $string;
            } else {
                return $this->render('landing', [
                    'error' => $error,
                    'type' => 0,
                ]);
            }
            if ($model->validate()) {
                // valid data received in $model
                if($model->save()){
                    $error = "Thank you for subscribing!";
                    return $this->render('landing',[
                        'error' => $error,
                        'type' => 1,
                    ]);
                } else {

                    $error = "Wrong email";
                    return $this->render('landing', [
                        'error' => $error,
                        'type' => 0,
                    ]);
                }
            } else {
                $error = "Wrong email";
                return $this->render('landing', [
                    'error' => $error,
                    'type' => 0,
                ]);
            }
        } else {
            return $this->render('index');
        }
    }

    public function actionIndex1()
    {
        $this->layout = "clean";
        $security = new Subscribers();
        $string = Yii::$app->request->post('email');
        $stringHash = '';
        if (!is_null($string)) {
            $stringHash = "WAT";
        }
        return $this->render('landing', [
            'model' => $security,
            'stringHash' => $stringHash,
        ]);
    }

    public function actionSubscribe()
    {
        if($this->LANDING) {
//            $model = new Subscribers();
//            $this->layout = "clean";
//            return $this->render('landing', ['model' => $model]);
            $model = new Subscribers();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                // valid data received in $model
                $model->save();
                // do something meaningful here about $model ...
                return $this->renderAjax('modal');
            } else {
                // either the page is initially displayed or there is some validation error
                return $this->renderAjax('modal');
            }
        } else {
            return $this->renderAjax('index');
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
