<?php

namespace app\src\controller;

use app\src\core\middlewares\AuthMiddleware;
use app\src\model\Application;
use app\src\model\Auth\LdapAuth;
use app\src\model\LoginForm;
use app\src\model\Request;
use app\src\model\Response;
use app\src\model\User;

class MainController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware());
    }


    public function contact(): string
    {
        return $this->render('contact');
    }

    public function profile(): string
    {
        return $this->render('profile');
    }
}
