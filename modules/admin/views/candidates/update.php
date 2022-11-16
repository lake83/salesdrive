<?php

/* @var $this yii\web\View */
/* @var $model app\models\Candidates */

$this->title = Yii::t('app', 'Candidate editing') . ': ' . $model->id;

echo $this->render('_form', ['model' => $model]) ?>