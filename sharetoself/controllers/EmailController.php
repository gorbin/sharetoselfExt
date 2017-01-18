<?php
/**
 * Created by PhpStorm.
 * User: Lesya
 * Date: 11.10.2016
 * Time: 20:21
 */

namespace app\controllers;

use app\models\Email;
use app\models\Verification;
use Aws\Common\Aws;
use Aws\Ses\SesClient;
use Exception;
use yii\rest\ActiveController;

class EmailController extends ActiveController
{

    public $modelClass = 'app\models\Email';

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

    }

    private function initSES()
    {
        try {
            $sdk = SesClient::factory(array(
                    'version' => 'latest',
                    'region' => 'us-west-2'
                )
            );
        } catch (Exception $error) {
            echo "Error: " . $error->getMessage();
            error_log("Gorbin Error: " . $error->getMessage());
            return null;
        }
        return $sdk;
    }

    public function actionSend()
    {
        if (!empty($_POST)) {

            $link = $_POST['link'];
            $title = $_POST['title'];
            $email = $_POST['email'];
            $id = $_POST['id'];

            $identify = Email::findOne(['idChrome' => $id, 'email' => $email]);

            $verify = Verification::findOne(['email' => $email]);
            if ($verify) {
                if ($verify->status == 1) {
                    if ($identify) {
                        $time = strtotime($identify->datetime);
                        $curtime = time();
                        if ($identify->ban < 5) {
                            if (($curtime - $time) > 1) {
                                $identify->updatePerSec($identify->id, false);
                                $this->sendEmail($link, $title, $email, $identify);
                            } else {
                                $identify->updatePerSec($identify->id, true);
                                if ($identify->perSec < 5) {
                                    $this->sendEmail($link, $title, $email, $identify);
                                } else {
                                    $identify->ban = $identify->ban + 1;
//                                        $identify->datetime = time();

                                    $identify->save();
                                    echo 'LIMIT';
                                }
                            }
                        } else {
                            echo 'BAN';
                        }
                    } else {
                        echo 'IDENTIFY1';
                    }
                } else {
                    $this->verify($email, $verify->hash);
                    echo 'VERIFY';
                }
            } else {
                echo 'VERIFY';
            }
        } else {
            echo 'IDENTIFY2';
        }
    }

    public function actionIdentify()
    {
        if (!empty($_POST)) {
            $id = $_POST['id'];
            $email = $_POST['email'];

            $verification = Verification::findOne(['email' => $email]);
            if (!$verification) {
                $hash = md5($email . time());
                $verification = new Verification();
                $verification->email = $email;
                $verification->hash = $hash;
                $verification->status = 0;
                $verification->save();
                $this->verify($email, $hash);
            }

            $this->identify($id, $email);

        }
    }

    public function identify($id, $email)
    {
        $model = Email::findOne(['idChrome' => $id, 'email' => $email]);
        if ($model) {
            echo true;
        } else {
            $model = new Email();
            $model->idChrome = $id;
            $model->email = $email;
            if ($model->save()) {
//                echo true;
            } else {
                echo 'IDENTIFY3';
            }
        }
    }

    public function verify($email, $hash)
    {

//        $email = "evgeny.kondrashkin@gmail.com";
//        $hash = md5($email . time());

        $link = 'http://sharetoself.com/activation/' . $hash;
        $htmlContent = str_replace('%link%', $link, file_get_contents(dirname(__DIR__) . '/views/mail/mail2.html'));
        $txtContent = str_replace('%link%', $link, file_get_contents(dirname(__DIR__) . '/views/mail/mail2.txt'));
        $title = "Please, verify your email address first";
        $client = $this->initSES();
        $params = $this->verifyMsg($email, $title, $htmlContent, $txtContent);
        try {
            $client->sendEmail($params);
        } catch (Exception $error) {
            error_log("SES Verify Error: " . $error->getMessage());
        }
    }

    public function sendEmail($url, $title, $email, $identify)
    {
        $client = $this->initSES();
        $params = $this->emailMsg($email, $title, $url);
        try {
            $identify->count = $identify->count + 1;
//            $identify->datetime = time();
            $identify->save();
            $client->sendEmail($params);
            echo "true";
        } catch (Exception $error) {
            error_log("SES Email Error: " . $error->getMessage());
        }
    }

    public function verifyMsg($email, $title, $htmlContent, $txtContent)
    {
        $SENDER = 'ShareToSelf <service@sharetoself.com>';
        $params = array(
            'Source' => $SENDER,
            'Destination' => array(
                'ToAddresses' => array($email),
                'CcAddresses' => array(),
                'BccAddresses' => array(),
            ),

            'Message' => array(
                'Subject' => array(
                    'Data' => $title,
                    'Charset' => 'UTF-8',
                ),
                'Body' => array(
                    'Html' => array(
                        'Data' => $htmlContent,
                        'Charset' => 'UTF-8',
                    ),
                    'Text' => array(
                        'Data' => $txtContent,
                        'Charset' => 'UTF-8',
                    ),
                ),
            ),
            'ReplyToAddresses' => array($SENDER)
        );
        return $params;
    }

    public function emailMsg($email, $title, $url)
    {
        $SENDER = 'ShareToSelf <service@sharetoself.com>';
        $params = array(
            'Source' => $SENDER,
            'Destination' => array(
                'ToAddresses' => array($email),
                'CcAddresses' => array(),
                'BccAddresses' => array(),
            ),
            'Message' => array(
                'Subject' => array(
                    'Data' => $title,
                    'Charset' => 'UTF-8',
                ),
                'Body' => array(
                    'Text' => array(
                        'Data' => $url,
                        'Charset' => 'UTF-8',
                    ),
                ),
            ),
            'ReplyToAddresses' => array($SENDER)
        );
        return $params;
    }
}
