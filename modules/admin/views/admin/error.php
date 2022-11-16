<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<!-- Main content -->
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>

            <p><b><?= nl2br(Html::encode($message)) ?></b></p>

            <p>
                <?= Yii::t('app', 'This error occurred while the web server was processing your request.<br />Please contact us if you think this is a server error. Thank you.') ?>
            </p>
        </div>
    </div>

</section>