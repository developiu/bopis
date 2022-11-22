<?php

namespace XPort\Mvc\Controller;

use XPort\Mapper\OrderMapper;
use XPort\Mapper\StoreMapper;
use XPort\Mvc\AbstractController;

class Store extends AbstractController
{
    public function index()
    {
        $mapper = new StoreMapper();

        if($_POST) {
            $store = ['name' =>$_POST['store_name'], 'address' => $_POST['store_address']];
            $mapper->save($store);
            header("location: /store");
            exit;
        }

        $store = $mapper->load();
        echo $this->getRenderer()->render('store/index',['store' => $store]);
    }

}