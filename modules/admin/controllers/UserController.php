<?php

namespace app\modules\admin\controllers;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminController
{
    public $modelClass = 'app\models\User';
    public $searchModelClass = 'app\models\UserSearch';
}