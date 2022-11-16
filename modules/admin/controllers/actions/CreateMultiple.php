<?php 
namespace app\modules\admin\controllers\actions;

use Yii;

class CreateMultiple extends \yii\base\Action
{
    use MultipleTraite;
    
    public $model;
    public $models;
    public $owner;
    public $view = 'createMultiple';
    public $redirect = ['index'];
    
    public function run()
    {
        $model = new $this->model;
        $models = [new $this->models];
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save() && $this->multipleCreate($model, $this->models, $this->owner)) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Record added.'));
                return $this->controller->redirect($this->redirect);
            }
        }
        return $this->controller->render($this->view, [
            'model' => $model,
            'models' => (empty($models)) ? [new $this->models] : $models
        ]);
    }
}