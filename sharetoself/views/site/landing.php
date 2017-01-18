<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$this->registerCssFile("/css/global.css");
$this->registerCssFile("/css/landing.css");
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="icon"
          type="image/png"
          href="./img/logoicon.png">
    <title>ShareToSelf extension</title>
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
<!--    <link rel="stylesheet" type="text/css" href="./css/css">-->
<!--    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" type="text/css" href="./css/styles.css">-->
<!--    <link rel="stylesheet" href="./css/jquery.modal.css" type="text/css" media="screen"/>-->
</head>

<body id="home">
<section class="main">
    <div id="Content" class="wrapper topSection">
        <div id="Header">
            <div class="wrapper">
                <div class="logo"><h1><img src="./img/logo1.png">ShareToSelf</h1></div>
            </div>
        </div>
        <h2>We are coming soon!!!</h2>
</section>

<section>
    <div class="bg invert">
        <div class="countdown styled">
            <div> <span>days</span></div>
            <div> <span>hrs</span></div>
            <div> <span>min</span></div>
            <div> <span>sec</span></div>
        </div>
    </div>
    </div>
</section>

<section class="subscribe spacing">
    <div class="container">
        <div id="subscribe">
            <h3>Subscribe To Get Notified</h3>
            <?= Yii::$app->session->getFlash('error'); ?>
            <!--            <form id="subscribeForm" method="post">-->
            <div class="mainRow">

            <?php Pjax::begin(); ?>
            <?= Html::beginForm(['site/form-submission'], 'post',
                ['data-pjax' => '']); ?>
            <div class="fieldRow">
            <?= Html::input('text', 'email', Yii::$app->request->post('email'),
                ['placeholder' => 'Enter your e-mail', 'class' => 'subscribeField']) ?>
                <div class="help-block-<?php echo $type?>"><?= $error ?></div>
            </div>
            <div class="linkRow">
            <?= Html::a('Submit', array('site/index'),
                array('class' => 'subscribeBtn', 'data' => array(
                    'method' => 'post',
                ),)) ?>
            </div>
            <?= Html::endForm() ?>
            <?php Pjax::end(); ?>

            </div>

            <div id="response"></div>
            <div id="socialIcons" style="text-align: center">
                <!-- AddToAny BEGIN -->

                <div class="a2a_kit a2a_kit_size_32 a2a_default_style" style="display: inline-block;">
                    <!-- <a class="a2a_dd" href="https://www.addtoany.com/share?linkurl=sharetoself.com&amp;linkname=ShareToSelf"></a> -->
                    <a class="a2a_button_facebook"></a>
                    <a class="a2a_button_twitter"></a>
                    <a class="a2a_button_google_plus"></a>
                    <a class="a2a_button_pinterest"></a>
                    <a class="a2a_button_linkedin"></a>
                    <a class="a2a_button_reddit"></a>
                    <a class="a2a_button_tumblr"></a>
                    <a class="a2a_button_telegram"></a>
                    <a class="a2a_button_vk"></a>
                </div>
                <script>
                    var a2a_config = a2a_config || {};
                    a2a_config.linkname = "ShareToSelf";
                    a2a_config.linkurl = "sharetoself.com";
                </script>
                <script async src="https://static.addtoany.com/menu/page.js"></script>

            </div>
        </div>
    </div>
</section>
<!-- <section class="features spacing">
  <div class="container">
    <h2 class="text-center">Features</h2>
    <div class="row">
      <div class="col-md-6">
        <div class="featuresPro">
          <div class="col-md-3 col-sm-3 col-xs-3 text-center"><img src="./img/icon-1.png" data-at2x="img/icon-1@2x.png" alt="Features"></div>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <h4>Lorem Lpsum</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit, beatae, esse, aspernatur, alias odio numquam incidunt perspiciatis aliquid voluptate sapiente.</p>
          </div>
        </div>
        <div class="featuresPro">
          <div class="col-md-3 col-sm-3 col-xs-3 text-center"><img src="./img/icon-2.png" data-at2x="img/icon-2@2x.png" alt="Features"></div>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <h4>Lorem Lpsum</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit, beatae, esse, aspernatur, alias odio numquam incidunt perspiciatis aliquid voluptate sapiente.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="featuresPro">
          <div class="col-md-3 col-sm-3 col-xs-3 text-center"><img src="./img/icon-3.png" data-at2x="img/icon-3@2x.png" alt="Features"></div>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <h4>Lorem Lpsum</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit, beatae, esse, aspernatur, alias odio numquam incidunt perspiciatis aliquid voluptate sapiente.</p>
          </div>
        </div>
        <div class="featuresPro">
          <div class="col-md-3 col-sm-3 col-xs-3 text-center"><img src="./img/icon-4.png" data-at2x="img/icon-4@2x.png" alt="Features"></div>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <h4>Lorem Lpsum</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit, beatae, esse, aspernatur, alias odio numquam incidunt perspiciatis aliquid voluptate sapiente.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->
<span class="bottomBlock">© 2016 <a href="https://github.com/gorbin" alt="webthemez">Gorbin</a></span>
<div id="alert" style="display:none;" class="modal">
    <p></p>
</div>

<!--Scripts-->
<!--<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>-->
<!--<script type="text/javascript" src="./js/jquery.countdown.js"></script>-->
<!--<script src="./js/jquery.modal.min.js" type="text/javascript" charset="utf-8"></script>-->
<!--<script type="text/javascript" src="./js/global.js"></script>-->

</body>
</html>
