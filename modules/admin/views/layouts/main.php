<?php
use yii\helpers\Html;
use kartik\alert\AlertBlock;
use kartik\dialog\Dialog;

/* @var $this \yii\web\View */
/* @var $content string */

app\assets\AdminAsset::register($this);

echo Dialog::widget([
   'libName' => 'krajeeDialog',
   'options' => [
       'type' => Dialog::TYPE_DEFAULT,
       'title' => false,
       'btnOKClass' => 'btn-success'
   ]
]);
$this->beginPage() ?>

<!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="<?= Yii::$app->assetManager->getBundle('app\assets\AdminAsset')->skin ?>">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?php 
        if (Yii::$app->errorHandler->exception == null) {
            echo $this->render('header');
            echo $this->render('left');
        } 
        echo AlertBlock::widget([
            'useSessionFlash' => true,
            'type' => AlertBlock::TYPE_GROWL
        ]);
        
        echo $this->render('content', ['content' => $content]);
        ?>
    </div>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>