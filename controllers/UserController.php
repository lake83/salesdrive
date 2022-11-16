<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\ResetPasswordForm;
use app\models\RemindForm;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use app\models\SignupForm;
use app\components\SiteHelper;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [$this->action->id],
                        'allow' => true,
                        'roles' => ['@', '?']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }
    
    /**
     * Sending a confirmation email to the user
     * 
     * @return string
     */
    public function actionEmailActivate()
    {
        if (Yii::$app->mailer->compose(['html' => 'emailActivation-html'], ['user' => Yii::$app->user->identity])
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setTo(Yii::$app->user->email)
            ->setSubject(Yii::t('app', 'Email confirmation to ') . Yii::$app->name)
            ->send()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'A confirmation email has been sent.'));    
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to send email.'));
        }
        return $this->redirect(SiteHelper::redirectByRole(Yii::$app->user->status));
    }
    
    /**
     * User Email Confirmation
     * 
     * @return string
     * @throws BadRequestHttpException if failed
     */
    public function actionEmailConfirmation($token)
    {
        if (empty($token) || !is_string($token)) {
            throw new BadRequestHttpException(Yii::t('app', 'The token cannot be empty.'));
        } else {
            $model = User::findOne(['auth_key' => $token]);
        }
        if (!$model || !$model->validateAuthKey($token)) {
            throw new BadRequestHttpException(Yii::t('app', 'Wrong token.'));
        } else {
            $model->is_active = 1;
            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Your email has been verified.'));
                return Yii::$app->user->isGuest ? $this->goHome() : $this->redirect(SiteHelper::redirectByRole(Yii::$app->user->status));
            }
        }
    }
    
    /**
     * Password Recovery Request
     * 
     * @return string
     */
    public function actionRemind()
    {
        $this->layout = '@app/modules/admin/views/layouts/main-login';
        
        $model = new RemindForm;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Instructions sent to your e-mail.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Error sending e-mail.'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('remind', ['model' => $model]);
    }
    
    /**
     * Change Password
     * 
     * @param string $token password change token
     * @return string
     * @throws BadRequestHttpException if failed
     */
    public function actionReset($token)
    {
        $this->layout = '@app/modules/admin/views/layouts/main-login';
        
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'New password saved.'));

            return $this->redirect(['site/index']);
        }
        return $this->render('reset', [
            'model' => $model
        ]);
    }
    
    /**
     * User registration
     * 
     * @return string
     */
    public function actionRegistration()
    {
        $model = new SignupForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Instructions for completing registration have been sent to your e-mail.'));
                Yii::$app->user->login($user);
                return $this->redirect(SiteHelper::redirectByRole($user->status));
            }
        }
        return $this->render('registration', [
            'model' => $model
        ]);
    }
}
?>