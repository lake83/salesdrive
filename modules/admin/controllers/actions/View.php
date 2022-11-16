<?php 
namespace app\modules\admin\controllers\actions;

class View extends \yii\base\Action
{
    use ActionsTraite;
    
    public $model;
    
    public function run()
    {
        return $this->controller->render('view', ['model' => $this->findModel($this->model)]);
    }
}