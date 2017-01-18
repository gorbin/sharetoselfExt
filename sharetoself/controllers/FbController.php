<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 10.10.2016
 * Time: 3:27
 */

namespace app\controllers;

use app\models\FbIdentity;
use yii\rest\ActiveController;
use pimax\FbBotApp;
use pimax\Messages\Message;


class FbController extends ActiveController
{

    public $modelClass = 'app\models\FbIdenity';

    private $LANDING = true;

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
    public function actionIndex()
    {

    }

    public function actionWebhook()
    {
        $verify_token = ""; // Verify token
        $token = ""; // Page token

        if (file_exists(__DIR__ . '/../config/fbconfig.php')) {
            $config = include __DIR__ . '/../config/fbconfig.php';
            $verify_token = $config['verify_token'];
            $token = $config['token'];
        }

        $bot = new FbBotApp($token);
//        $data = json_decode(file_get_contents("php://input"), true);
//        Yii::info($data, 'apiRequest');

        if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {
            echo $_REQUEST['hub_challenge'];
        } else {

            $data = json_decode(file_get_contents("php://input"), true);

            if ((!empty($data['entry'][0]['messaging'][0]['optin']['ref']))) {
                $idChrome = $data['entry'][0]['messaging'][0]['optin']['ref'];
                $fbid = $data['entry'][0]['messaging'][0]['sender']['id'];

                $model = FbIdentity::findOne(array(
                    'idChrome' => $idChrome,
                ));
                if (!$model) $model = new FbIdentity();
                $model->idChrome = $idChrome;
                $model->fbid = $fbid;

                $model->save();
                $bot->send(new Message($data['entry'][0]['messaging'][0]['sender']['id'], 'You sharetoself bot updated, just save something!'));
            } else
                if (!empty($data['entry'][0]['messaging'])) {
                    $bot->send(new Message($data['entry'][0]['messaging'][0]['sender']['id'], 'Hi there! Check https://sharetoself.com/'));
                }
        }
    }

    private function initBot()
    {
        $token = ""; // Page token

        if (file_exists(__DIR__ . '/../config/fbconfig.php')) {
            $config = include __DIR__ . '/../config/fbconfig.php';
            $token = $config['token'];
        }

        return new FbBotApp($token);
    }

    public function actionSend()
    {
        if (!empty($_POST)) {
            $fb = $_POST['fb'];
            $id = $_POST['id'];
            $url = $_POST['link'];
            $title = $_POST['title'];

            if (empty($fb)) {
                $model = FbIdentity::findOne(['idChrome' => $id]);
                if ($model) {
                    $fb = $model->fbid;
                }
            }

            if (!empty($fb)) {
                $bot = $this->initBot();//new FbBotApp($token);
                $bot->send(new Message($fb, $title . ': ' . $url));
                echo 'true:' . $fb;
            } else {
                echo 'IDENTIFY';
            }

        }

    }


}
