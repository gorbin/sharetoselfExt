<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 10.10.2016
 * Time: 3:27
 */

namespace app\controllers;

use app\models\Slack;
use yii\rest\ActiveController;

class SlackController extends ActiveController
{
    public $modelClass = 'app\models\Slack';

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
        if (!empty($_REQUEST['code']) && !empty($_REQUEST['state'])) {
            $code = $_REQUEST['code'];
            $uid = $_REQUEST['state'];
            $model = Slack::findOne(array(
                'uid' => $uid,
            ));
            if (!$model) $model = new Slack();
            $model->uid = $uid;
            $model->code = $code;

            $this->getToken($_REQUEST['code'], $model);
        }

    }

    public function actionSend()
    {
        $this->sendMessage("Test message - user - asUser", "xoxb-135846982019-oPb3hNZFlh0dbMgwznpkcTF1", "U0275J86C");
    }

    public function getToken($code, $model){
        $postfields = array('client_id'=>'2243620212.87255959762',
                            'client_secret'=>'223369ae88f5f391e9735511df047a7d',
                            'code'=>$code);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://slack.com/api/oauth.access');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        $result = curl_exec($ch);
        $json = json_decode($result, true);
        $model->access_token = $json['access_token'];
        $model->scope = $json['scope'];
        $model->user_id = $json['user_id'];
        $model->team_name = $json['team_name'];
        $model->team_id = $json['team_id'];
        $model->bot_user_id = $json['bot']['bot_user_id'];
        $model->bot_access_token = $json['bot']['bot_access_token'];
        var_dump($model);
        $model->save();
        $this->sendMessage("Great! Just save something!", $model->bot_access_token, $model->user_id);

    }

    public function sendMessage($message, $token, $channel){
        $postfields = array("token"=>$token, "channel" => $channel, "text"=>$message, "as_user"=>true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://slack.com/api/chat.postMessage");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
        $result = curl_exec($ch);
        var_dump($result);
    }
}
