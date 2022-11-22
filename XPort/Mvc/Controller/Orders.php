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

    /**
     * Accetta due parametri: id e status; id può essere una stringa o un array di stringhe. Se id è una stringa
     * aggiorna lo status dell'ordine corrispondente, altrimenti di tutti gli ordini corrispondenti.
     */
    public function updateStatus()
    {
        if(!isset($_GET['id']) || !isset($_GET['status'])) {
            echo json_encode(['status' => 'error', 'message' => "Alcuni parametri obbligatori non sono stati specificati"]);
            exit;
        }
        $orderMapper = new OrderMapper();

        $ids = $_GET['id'];

        $status = $_GET['status'];
        if(!in_array($status, $orderMapper->getAllowedStatuses())) {
            echo json_encode(['status' => 'error', 'message' => "Lo stato '{$_GET['status']}' non è ammesso"]);
            exit;
        }

        $orderMapper->updateOrderStatus($ids, $status);

        echo json_encode(['status' => 'success']);
        exit;
    }
}