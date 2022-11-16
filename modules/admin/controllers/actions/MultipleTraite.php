<?php
namespace app\modules\admin\controllers\actions;

use Yii;
use app\models\MultipleModel;
use yii\helpers\ArrayHelper;

trait MultipleTraite
{
    /**
     * Create Records
     * @param object $model
     * @param array $models models to save
     * @param string $owner attribute for linking to parent record
     * @return boolean
     */
    protected function multipleCreate($model, $models, $owner)
    {
        $models = MultipleModel::createMultiple($models);
        MultipleModel::loadMultiple($models, Yii::$app->request->post());

        if (MultipleModel::validateMultiple($models)) {
            $flag = true;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                foreach ($models as $one) {
                    $one->{$owner} = $model->id;
                    if (!($flag = $one->save())) {
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return true;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        return false;
    }
    
    /**
     * Create Records
     * @param object $model
     * @param string $modelsClass models class
     * @param array $models models to save
     * @param string $owner attribute for linking to parent record
     * @return boolean
     */
    protected function multipleUpdate($model, $modelsClass, $models, $owner)
    {
        $oldIDs = ArrayHelper::map($models, 'id', 'id');
        $models = MultipleModel::createMultiple($modelsClass::classname(), $models);
        MultipleModel::loadMultiple($models, Yii::$app->request->post());
        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($models, 'id', 'id')));

        if (MultipleModel::validateMultiple($models)) {
            $flag = true;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!empty($deletedIDs)) {
                    $modelsClass::deleteAll(['id' => $deletedIDs]);
                }
                foreach ($models as $one) {
                    $one->{$owner} = $model->id;
                    if (!($flag = $one->save())) {
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return true;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
        return false;
    }
}