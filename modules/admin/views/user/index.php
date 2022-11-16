<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
?>

<p><?= Html::a(Yii::t('app', 'Create user'), ['create'], ['class' => 'btn btn-success']) ?></p>

<?= GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax' => true,
    'export' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
            [
                'attribute' => 'status',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    $searchModel->getStatus(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', '- choose -')]
                ),
                'value' => function ($model, $index, $widget) {
                    return $model->getStatus($model->status);}
            ],
            SiteHelper::is_active($searchModel),
            SiteHelper::created_at($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);
?>