<?php
namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use app\models\User as modelUser;
use yii\web\NotFoundHttpException;

class SiteHelper
{
    /**
     * Filter output and values in the Active column GridView
     * @param object $searchModel
     * @return array
     */
    public static function is_active($searchModel)
    {
        return [
            'class' => 'pheme\grid\ToggleColumn',
            'attribute' => 'is_active',
            'filter' => Html::activeDropDownList(
                $searchModel,
                'is_active',
                [0 => Yii::t('app', 'Not active'), 1 => Yii::t('app', 'Active')],
                ['class' => 'form-control', 'prompt' => Yii::t('app', '- choose -')]
            ),
            'onText' => Yii::t('app', 'On'),
            'offText' => Yii::t('app', 'Off')
        ];
    }
    
    /**
     * Filter output and values in the Created column GridView
     * @param object $searchModel
     * @return array
     */
    public static function created_at($searchModel)
    {
        return [
            'attribute' => 'created_at',
            'format' => ['date', 'php:j M, G:i'],
            'filter' => \yii\jui\DatePicker::widget([
                'model' => $searchModel,
                'options' => ['class' => 'form-control'],               
                'attribute' => 'created_at',
                'dateFormat' => 'dd.MM.yyyy'
            ])
        ];
    }
    
    /**
     * Image resize
     * @param string $image
     * @param int $width
     * @param mixed $height  
     * @return string image Url
     */
    public static function resized_image($image = '', $width, $height = null)
    {
        $url = false;
        if (!empty($image)) {
            $image = explode('/', $image);
            $last = end(array_keys($image));
            $file = end($image);
            unset($image[$last]);
            $dir_path = !empty($image) ? '/' . implode('/', $image) : '';
            $dir = Yii::getAlias('@webroot/images/uploads/') . $width . 'x' . $height . $dir_path;
            $img = $dir . '/' . $file;
            
            if (file_exists($img)) {
                $url = true;
            } else {
                FileHelper::createDirectory($dir);
                $original = Yii::getAlias('@webroot/images/uploads/source') . $dir_path . '/' . $file;
                try {
                    if (file_exists($original) && filesize($original) < 10000000) {
                        Image::thumbnail($original, $width, $height)->save($img, ['quality' => 100]);
                    }
                    $url = true;
                } catch (ErrorException $e) {
                    $url = false;
                }
            }
        }
        return $url ? '/images/uploads/' . $width . 'x' . $height . $dir_path . '/' . $file : '/images/anonymous.png';
    }
    
    /**
     * Redirect a user based on their status
     * 
     * @param integer $status user status
     * @return array
     */
    public static function redirectByRole($status)
    {
        switch($status) {
            case modelUser::ROLE_ADMIN: $redirect = ['admin/user/index']; break;
            default: $redirect = ['site/index']; break;
        }
        return $redirect;
    }
}