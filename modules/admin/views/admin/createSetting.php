<?php
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['id' => 'createSettingForm', 'layout' => 'horizontal']);

echo $form->field($model, 'label')->label(Yii::t('app', 'Title'));

echo $form->field($model, 'value')->textArea()->label(Yii::t('app', 'Value'));

echo $form->field($model, 'name')->label(Yii::t('app', 'Alias'));

echo $form->field($model, 'rules')->label(Yii::t('app', 'Validation Rule'));

echo $form->field($model, 'icon')->label(Yii::t('app', 'Pictogram'));

echo $form->field($model, 'hint')->textArea()->label(Yii::t('app', 'Note'));

echo \yii\helpers\Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']);

ActiveForm::end(); ?>