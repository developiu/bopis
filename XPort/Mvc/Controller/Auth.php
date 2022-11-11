<?php

namespace XPort\Mvc\Controller;

use XPort\Mvc\AbstractController;
use XPort\Auth as AuthService;

class Auth extends AbstractController
{
    public function logout()
    {
        AuthService::logout();
        header('location: /');
        exit;
    }
    
}