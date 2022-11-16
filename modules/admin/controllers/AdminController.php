<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\User;
use yii\base\DynamicModel;
use yii\caching\TagDependency;

class AdminController extends Controller
{
    public $layout = '@app/modules/admin/views/layouts/main';
    
    /**
     * @var string Data model class
     */
    public $modelClass;
    
    /**
     * @var string Search Model Class
     */
    public $searchModelClass;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->status == User::ROLE_ADMIN) {
            Yii::$app->errorHandler->errorAction = 'admin/admin/error';
        }
        parent::init();
    }
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [$this->action->id],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() {
                            return Yii::$app->user->status == User::ROLE_ADMIN;                    
                        }
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
     * @inheritdoc
     */
    public function actions()
    {
        $actionPath = 'app\modules\admin\controllers\actions\\';
        
        return [
            'index' => [
                'class' => $actionPath . 'Index',
                'search' => $this->searchModelClass
            ],
            'create' => [
                'class' => $actionPath . 'Create',
                'model' => $this->modelClass
            ],
            'update' => [
                'class' => $actionPath . 'Update',
                'model' => $this->modelClass
            ],
            'delete' => [
                'class' => $actionPath . 'Delete',
                'model' => $this->modelClass
            ],
            'toggle' => [
                'class' => \pheme\grid\actions\ToggleAction::className(),
                'modelClass' => $this->modelClass,
                'attribute' => 'is_active'
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }
    
    /**
     * Editing settings in the admin panel
     * 
     * @return array|object
     */
    public function actionSettings()
    {
        $request = Yii::$app->request;
                
        if (($model = $request->post('DynamicModel')) && Yii::$app->db->createCommand('UPDATE settings SET value="' . array_values($model)[0] . '" WHERE name="' . array_keys($model)[0] . '"')->execute()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            TagDependency::invalidate(Yii::$app->cache, 'settings');        
            return [
                'message' => '<div class="alert-success alert fade in">' . Yii::t('app', 'Changes saved.') . '</div>',
                'name' => array_keys($model)[0],
                'value' => array_values($model)[0]
            ];
        }
        if ($request->isAjax && $field = $request->post('field')) {
            $param = (new \yii\db\Query())->from('settings')->where(['name' => $field])->one();
            $model = new DynamicModel([$field]);
            $model->addRule($field, 'required')->addRule($field, $param['rules']);
            return $this->renderAjax('settings', ['model' => $model, 'param' => $param]);            
        }
    }
    
    /**
     * Creating settings in the admin panel
     * 
     * @return string|object
     */
    public function actionCreateSetting()
    {
        if ($model = Yii::$app->request->post('DynamicModel')) {
            if (Yii::$app->db->createCommand()->insert('settings', $model)->execute()) {
                TagDependency::invalidate(Yii::$app->cache, 'settings');
                Yii::$app->response->data =  '<div class="alert-success alert fade in">' . Yii::t('app', 'Changes saved.') . '</div>';
            }
            Yii::$app->end();
        }
        if (Yii::$app->request->isAjax) {
           $model = new DynamicModel(['name', 'value', 'label', 'rules', 'icon', 'hint']);
           $model->addRule(['name', 'value', 'label', 'rules'], 'required')->addRule(['icon', 'hint'], 'string');
           return $this->renderAjax('createSetting', ['model' => $model]); 
        }
    }
    
    /**
     * Clearing the entire application cache
     * 
     * @return string
     */
    public function actionClearCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Cache flushing completed successfully.'));
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}