<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "candidates".
 *
 * @property int $id
 * @property string $name
 * @property int $birth_date
 * @property int $experience
 * @property string $frameworks
 * @property string $cv
 * @property string $comment
 * @property int $is_active
 * @property int $created_at
 * @property int $updated_at
 */
class Candidates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'candidates';
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'birth_date', 'experience', 'frameworks'], 'required'],
            [['experience', 'is_active', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['frameworks'], 'safe'],
            ['birth_date', 'date', 'format' => 'php:d.m.Y', 'timestampAttribute' => 'birth_date'],
            [['name', 'cv'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'experience' => Yii::t('app', 'Experience'),
            'frameworks' => Yii::t('app', 'Frameworks'),
            'cv' => Yii::t('app', 'CV'),
            'comment' => Yii::t('app', 'Comment'),
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
        if (is_array($this->frameworks)) {
            $this->frameworks = implode(',', $this->frameworks);
        }
        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->frameworks = explode(',', $this->frameworks);
        
        parent::afterFind();
    }
    
    public function getFrameworks()
    {
        return [1 => 'Yii1', 2 => 'Yii2', 3 => 'Laravel', 4 => 'Symphony', 5 => 'Zend'];
    }
}
