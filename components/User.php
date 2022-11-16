<?php
namespace app\components;

use Yii;

/**
 * This allows us to do "Yii::$app->user->something" by adding getters
 * like "public function getSomething()"
 *
 * So we can use variables and functions directly in `Yii::$app->user`
 */
class User extends \yii\web\User
{
    /**
     * User Status
     */
    public function getStatus()
    {
        return Yii::$app->user->identity->status;
    }
    
    /**
     * User E-mail
     */
    public function getEmail()
    {
        return Yii::$app->user->identity->email;
    }
    
    /**
     * User name
     */
    public function getName()
    {
        return Yii::$app->user->identity->username;
    }
}
