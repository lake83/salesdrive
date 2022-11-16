<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $status;
    public $username;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'match', 'pattern' => '/^(([a-z\(\)\s]+)|([а-яё\(\)\s]+))$/isu'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['username', 'trim'],

            [['email', 'status'], 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 100],
            ['email', 'trim'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'This E-mail is already registered.')]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Name'),
            'email' => 'E-mail'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = $this->status;
            $random_pass = Yii::$app->security->generateRandomString(6);
            $user->new_password = $random_pass;
            $user->generateAuthKey();
            if ($user->save()) {
                Yii::$app->mailer->compose(['html' => 'newUser-html'], [
                    'user' => $user,
                    'password' => $random_pass
                ])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject(Yii::t('app', 'Register on ') . Yii::$app->name)
                ->send();
                    
                return $user;
            }
        }
        return null;
    }
}