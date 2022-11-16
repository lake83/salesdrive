<?php

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'User Editing') . ': ' . $model->id;

echo $this->render('_form', ['model' => $model]) ?>