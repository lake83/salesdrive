<?php
namespace app\components;

use xvs32x\tinymce\Tinymce;

class RedactorTinymce extends Tinymce
{
    public $pluginOptions = [
        'plugins' => [
            'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table responsivefilemanager'
        ],
        'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | code | removeformat',
        'language' => 'en',
        'menubar' => false,
        'width' => 700,
        'height' => 250
    ];
    public $fileManagerOptions = [
        'configPath' => [
            'upload_dir' => '/files/',
            'current_path' => '../../../files/',
            'thumbs_base_path' => '../../../files/thumbs/'
        ]
    ];
    
    public function __construct()
    {
        $this->pluginOptions['filemanager_title'] = \Yii::t('app', 'File manager');
        $this->pluginOptions['setup'] = new \yii\web\JsExpression("function(editor){jQuery('form button[type=submit]').on('click', function() {editor.save()})}"); 
    }
}