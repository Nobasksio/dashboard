<?php

namespace application\controller;

use \application\service\Service;
use \application\service\FrontController;
use \application\model\UserModel;

/**
 * /?path=auth/{action}
 */
class AuthController extends FrontController
{

    public function action_index()
    {

        return $this->view->render("user/index", [
            "title" => "Вход",
            "version" => ""
        ]);
    }

    public function action_cabinet()
    {
        if (isset($_SESSION['id'])) {

            $User = new UserModel();
            $UserInfo = $User->getUserById($_SESSION['id']);

            return $this->view->render("user/cabinet", [
                "massage" => "Добро пожаловать",
                "name" => $UserInfo[0]['name'],
                "login" => $UserInfo[0]['login'],
                "pass" => $UserInfo[0]['pass'],
                "history" => array_slice($this->history->arr_url, -5)
            ]);
        } else {
            return $this->action_index();
        }

    }

    public function action_login()
    {

            if (!$this->request->isPost()) {
                return $this->action_index();
            } else {
                $User = new UserModel();
                $UserInfo = $User->getUserByLoginAndPass($this->request->get("login"), $this->request->get("pass"));

                if (count($UserInfo) == 1) {

                    return $this->view->render("user/cabinet", [
                        "massage" => "Добро пожаловать",
                        "name" => $UserInfo[0]['name'],
                        "login" => $UserInfo[0]['login'],
                        "pass" => $UserInfo[0]['pass'],
                        "history" => array_slice($this->history->arr_url, -5)
                    ]);

                } else {
                    return $this->view->render("user/index", [
                        "massage" => "Такой пары логин/пароль в базе не обнаружено",
                        "login" => $this->request->get("login"),
                        "password" => $this->request->get("password"),
                    ]);
                }
            }



    }

    public function action_logout()
    {
        $_SESSION = array();
        return $this->action_index();

    }

    public function action_profile()
    {

    }
}