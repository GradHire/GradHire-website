<?php

namespace app\src\controller;

use app\src\core\exception\ForbiddenException;
use app\src\core\exception\NotFoundException;
use app\src\core\exception\ServerErrorException;
use app\src\model\Application;
use app\src\model\Auth;
use app\src\model\Request;

/**
 * @throws ForbiddenException
 * @throws NotFoundException
 * @throws ServerErrorException
 */
class TestController extends AbstractController
{
    /**
     * @throws ServerErrorException
     */
    public function user_test(Request $req): void
    {
        if (session_status() !== PHP_SESSION_NONE)
            session_destroy();
        $user = Auth::load_user_by_id($req->getRouteParams()["id"]);
        Auth::generate_token($user, "true");
        Application::$app->response->redirect('/');
    }

    public function page1(): string
    {
        return $this->render('tests/page1', ['currentTab' => $_SESSION['currentTab'] ?? 'tab1']);
    }

    public function page2(): string
    {
        return $this->render('tests/page2');
    }

    public function page3(): string
    {
        return $this->render('tests/page3');
    }
}
