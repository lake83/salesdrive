<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Candidates */
/* @var $form yii\bootstrap\ActiveForm */

$form = ActiveForm::begin(['layout' => 'horizontal']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birth_date')->widget(\yii\jui\DatePicker::className(), [
        'options' => ['class' => 'form-control'],
        'dateFormat' => 'dd.MM.yyyy'
    ]) ?>
    
    <?= $form->field($model, 'experience') ?>

    <?= $form->field($model, 'frameworks')->checkboxList($model->getFrameworks()) ?>
    
    <?= $form->field($model, 'cv')->widget(\app\components\FilemanagerInput::className()) ?>
    
    <?= $form->field($model, 'comment')->widget(\app\components\RedactorTinymce::className()) ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>