<?php

namespace app\modules\admin\controllers;

/**
 * CandidatesController implements the CRUD actions for Candidates model.
 */
class CandidatesController extends AdminController
{
    public $modelClass = 'app\models\Candidates';
    public $searchModelClass = 'app\models\CandidatesSearch';
}