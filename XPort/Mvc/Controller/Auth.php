<?php

namespace XPort\Mvc\Controller;

use XPort\Mvc\AbstractController;
use XPort\Auth as AuthService;

class Auth extends AbstractController
{
    public function login()
    {
        $message = '&nbsp';
        if($_POST) {
            $accessCode = $_POST['access_code'] ?? '';
            if(AuthService::login($accessCode)) {
                header('location: /');
                exit;
            }
            else {
                $message = "Credenziali invalide, riprovare";
            }
        }

        echo $this->getRenderer()->render('auth/login',['message' => $message]);
    }

    public function logout()
    {
        AuthService::logout();
        header('location: /');
        exit;
    }

}