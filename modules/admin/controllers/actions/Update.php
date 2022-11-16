<?php 
namespace app\modules\admin\controllers\actions;

class Update extends \yii\base\Action
{
    use ActionsTraite;
    
    public $model;
    public $scenario;
    public $redirect = ['index'];
    
    public function run()
    {
        return $this->actionBody($this->model, \Yii::t('app', 'Changes saved.'), 'update', $this->redirect, $this->scenario);
    }
}