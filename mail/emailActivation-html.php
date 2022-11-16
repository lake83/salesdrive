<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$activationLink = Yii::$app->urlManager->createAbsoluteUrl(['/user/email-confirmation', 'token' => $user->auth_key]);
?>

<table style="padding:0;background-color:#e5e5e5;width:100%;border-collapse:collapse;border-spacing:0; text-align:center; vertical-align:top; margin:30px auto;">
    <tbody>
        <tr style="padding:0;text-align:center;vertical-align:top;width:100%;" align="center">
            <td>                 
                <h1 style="padding:0 25px;color:#485671;font:400 24px Arial;margin:35px 0;"><?= Yii::t('app', 'Hello') . ', ' . Html::encode($user->username) ?></h1>
                <div>
                    <span style="padding:0 25px;color:#4a5773;font:400 16px Arial;margin-bottom:30px;"><?= Yii::t('app', 'To confirm your email go to the link') ?></span>
                    <a href="<?= $activationLink ?>" style="display:block;width:250px;height:40px;text-align:center;background-color:#1c69ff;color:#ffffff;font:400 16px Arial;text-transform:uppercase;text-decoration:none;line-height:40px;margin:30px auto;"><?= Yii::t('app', 'Confirm') ?></a>
                </div>
                <hr style="border:0;height:1px;background-color:#687aa1;margin:5px 0 35px 0;width:calc(100% - 50px);margin-left:25px;"/>
                <span style="padding:0 25px;color:#4a5773;font:400 16px Arial;margin-bottom:30px;"><?= Yii::t('app', 'If you cannot confirm the request, please click on the link or<br />paste it into the address bar of the browser') ?></span>
                <a href="<?= $activationLink ?>" style="color:#1c69ff;font:700 16px Arial;text-decoration:underline;padding:30px 0;display: block;"><?= $activationLink ?></a>
            </td>
        </tr>
    </tbody>            
</table>