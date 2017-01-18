<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 13.10.2016
 * Time: 14:30
 */

namespace app\controllers;


use app\models\Verification;
use Yii;
use yii\rest\ActiveController;

class ActivationController extends ActiveController
{

    public $modelClass = 'app\models\verification';


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

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
    public function actionIndex($id = null)
    {
        $this->layout = "clean2";
        $callbackString = 'If you got problems - contact with us: 
                  <a href="mailto:service@sharetoself.com&subject=Wrong%20activation%20code">service@sharetoself.com</a> ';

        if ($id != null) {


//        if (!empty($_GET)) {
//            $hash = $_GET['code'];
//        } else {
////            $htmlContent = str_replace('%error%', $callbackString, file_get_contents('error.php'));
////            $output = "<script>console.log( 'Debug Objects: Error: setup !GET! params' );</script>";
////            return $htmlContent;
//            return $this->render('error', [
//                'error' => $callbackString,
//            ]);
//        }
            $hash = $id;

            $model = Verification::findOne(['hash' => $hash]);
            if($model) {
                switch ($model->status) {
                    case 0:
                        $model->status = 1;
                        $model->save();
                        return $this->render('verification');
                        break;
                    case 1:
                        $error = 'This activation code was activated earlier. ' . $callbackString;
                        return $this->render('error', [
                            'error' => $error,
                        ]);
                        break;
                    default:
                        $error = 'Wrong activation code, check your email for right activation link. ' . $callbackString;
                        return $this->render('error', [
                            'error' => $error,
                        ]);
                        break;
                }
            } else {
                $error = 'Wrong activation route, check your email for right activation link. ' . $callbackString;
                return $this->render('error', [
                    'error' => $error,
                ]);
            }
        } else {
            return $this->render('error', [
                'error' => $callbackString,
            ]);
        }
    }

}