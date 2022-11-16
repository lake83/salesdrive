<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;
use app\models\Candidates;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Candidates');
?>

<p><?= Html::a(Yii::t('app', 'Create candidate'), ['create'], ['class' => 'btn btn-success']) ?></p>

<?= GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax' => true,
    'export' => false,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'birth_date',
                'format' => ['date', 'php:j M, G:i'],
                'filter' => \yii\jui\DatePicker::widget([
                    'model' => $searchModel,
                    'options' => ['class' => 'form-control'],               
                    'attribute' => 'birth_date',
                    'dateFormat' => 'dd.MM.yyyy'
                ])
            ],
            'experience',
            [
                'attribute' => 'frameworks',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'frameworks',
                    $searchModel->getFrameworks(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', '- choose -')]
                ),
                'value' => function ($model, $index, $widget) {
                    $frameworks = $model->getFrameworks();
                    $result = [];
                    
                    foreach ($model->frameworks as $one) {
                        $result[] = $frameworks[$one];
                    }
                    return implode(',', $result);
                }
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