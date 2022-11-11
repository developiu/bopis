<?php

namespace XPort\Mvc\Controller;

use XPort\Mapper\ProductMapper;
use XPort\Mvc\AbstractController;

class Products extends AbstractController
{
    public function index()
    {
        $mapper = new ProductMapper();
        $prodotti = $mapper->fetchAll();

        echo $this->getRenderer()->render('products/index',['prodotti' => $prodotti]);
    }
    
}