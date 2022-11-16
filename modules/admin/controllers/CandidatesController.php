<?php

namespace app\modules\admin\controllers;

use Yii;

/**
 * CandidatesController implements the CRUD actions for Candidates model.
 */
class CandidatesController extends AdminController
{
    public $modelClass = 'app\models\Candidates';
    public $searchModelClass = 'app\models\CandidatesSearch';
    
    public function actionDownload($id)
    {
        if (($cv = \app\models\Candidates::find()->select('cv')->where(['id' => $id])->scalar()) &&
            file_exists(Yii::getAlias('@webroot/files/') . $cv)
        ) {
            return Yii::$app->response->sendFile(Yii::getAlias('@webroot/files/') . $cv, $cv)->send();
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to send file.'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
}