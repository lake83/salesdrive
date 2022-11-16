<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\ForbiddenHttpException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property integer $status
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $is_active
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 */

class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN = 10;
    const ROLE_USER = 20;
    
    /**
     * @var string Used when changing the password in the user profile
     */
    public $new_password;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            
            ['email', 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 255],
            
            [['username', 'email'], 'trim'],
            [['password_hash', 'new_password'], 'string', 'min' => 6],
            [['status', 'is_active', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'match', 'pattern' => '/^(([a-z\(\)\s]+)|([а-яё\(\)\s]+))$/isu'],
            ['new_password', 'required', 'on' => 'insert']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => Yii::t('app', 'Name'),
            'email' => 'E-mail',
            'password_hash' => Yii::t('app', 'Password'),
            'new_password' => Yii::t('app', 'New password'),
            'status' => Yii::t('app', 'Status'),
            'is_active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated')
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$insert && $this->status == self::ROLE_ADMIN && $this->is_active == 0 && (self::find()->where(['status' => self::ROLE_ADMIN, 'is_active' => 1])->count()) == 1) {
            throw new ForbiddenHttpException(Yii::t('app', 'There must be at least one current administrator.'));
        }
        if ($insert) {
            $this->generateAuthKey();
        }
        if (!empty($this->new_password)) {
            $this->setPassword($this->new_password);
        }
        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if ($this->status == self::ROLE_ADMIN && self::find()->where(['status' => self::ROLE_ADMIN, 'is_active' => 1])->count() == 1) {
            throw new ForbiddenHttpException(Yii::t('app', 'There must be at least one current administrator.'));
        }
        return parent::beforeDelete();
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(Yii::t('app', '"findIdentityByAccessToken" is not implemented.'));
    }
    
    /**
     * Finds user by username or email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['is_active' => 1])->andWhere(['or', ['username' => $username], ['email' => $username]])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token]);
    }
    
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * Returns a list of user statuses or name
     * 
     * @param integer $key key in an array of names
     * @return mixed
     */
    public static function getStatus($key = null)
    {
        $array = [
            self::ROLE_ADMIN => Yii::t('app', 'Administrator'),
            self::ROLE_USER => Yii::t('app', 'User')
        ];
        return is_null($key) ? $array : $array[$key];
    }
}