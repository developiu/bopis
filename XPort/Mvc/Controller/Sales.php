<?php

namespace XPort\Mvc\Controller;

use XPort\Mapper\OrderMapper;
use XPort\Mvc\AbstractController;

class Sales extends AbstractController
{
    public function index()
    {
        echo $this->getRenderer()->render('sales/index');
    }

}