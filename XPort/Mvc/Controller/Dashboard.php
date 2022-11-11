<?php

namespace XPort\Mvc\Controller;

use XPort\Mvc\AbstractController;

class Dashboard extends AbstractController
{
    public function index()
    {
        echo $this->getRenderer()->render('dashboard/index');
    }
    
}