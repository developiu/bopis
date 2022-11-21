<?php

namespace XPort\Mvc\Controller;

use XPort\Mapper\OrderMapper;
use XPort\Mvc\AbstractController;

class Orders extends AbstractController
{
    public function index()
    {
        $mapper = new OrderMapper();
        $orders = $mapper->fetchAll();

        echo $this->getRenderer()->render('orders/index',['ordini' => $orders]);
    }

    public function deleteOrder()
    {
        if(!isset($_GET['id'])) {
            http_response_code(403);
            echo $this->getRenderer()->render('error-pages/general_error',['error_code' => 403,'message' => "Accesso vietato"]);
            exit;
        }
        $id = $_GET['id'];
        $mapper = new OrderMapper();
        $mapper->deleteOrder($id);
        header("location: /orders");
    }
}