<?php

namespace XPort\Mvc\Controller;

use GuzzleHttp\Client;
use XPort\Bopis\Order\OrderService;
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

//    public function cancelOrder()
//    {
//        if(!isset($_GET['id'])) {
//            http_response_code(403);
//            echo $this->getRenderer()->render('error-pages/general_error',['error_code' => 403,'message' => "Accesso vietato"]);
//            exit;
//        }
//        $id = $_GET['id'];
//        $mapper = new OrderMapper();
//        try {
//            $mapper->cancelOrder($id);
//        }
//        catch(\Exception $e) {
//            var_dump($e->getMessage());exit;
//        }
//        header("location: /orders");
//        exit;
//    }

    /**
     * Accetta due parametri: id e status; id può essere una stringa o un array di stringhe. Se id è una stringa
     * aggiorna lo status dell'ordine corrispondente, altrimenti di tutti gli ordini corrispondenti.
     *
     * La risposta può essere una delle seguenti:
     * ['status' => 'success']: tutti gli ordini sono stati aggiornati
     * ['status' => 'error', 'message' => <messaggio esplicativo>] è avvenuto un errore: nessuno stato è stato modificato
     * ['status' => 'incomplete', 'problematic_ids' => [ .... ], 'message' => 'messaggio esplicativo' ]: non è spossibile
     * modificare qualche ordine. problematic_ids contiene gli id degli ordini che non possono essere modificati
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

        $compatibleOrders = $orderMapper->ordersCompatibleWithNewStatus($_GET['status'], $ids);
        $compatibleOrderIds = [];
        foreach($compatibleOrders as $ord) {
            $compatibleOrderIds[] = $ord['id'];
        }

        // riforziamo gli indici a partire da zero altrimenti json_encode traduce l'array come oggetto
        $incompatibleOrderIds = array_values(array_diff($ids,$compatibleOrderIds));
        if($incompatibleOrderIds) {
            echo json_encode(['status' => 'incomplete', 'problematic_ids' => $incompatibleOrderIds,
                'message' => "Lo stato degli ordini evidenziati è incompatibile con lo stato richiesto"]);
            exit;
        }

//        $client = new Client();
//        $orderService = new OrderService($client);
//        $successful = true;
//        foreach($ids as $id) {
//            $successful = $successful && $orderService->updateStatus($id, $status);
//            if($successful) {
//                $orderMapper->updateOrderStatus($id, $status);
//            }
//            else {
//                break;
//            }
//        }

        $numberModified = $orderMapper->updateOrderStatus($ids, $status);

        if($numberModified === 0) {
            echo json_encode([
                'status' => 'warn',
                'message' => 'nessun ordine modificato'
            ]);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Ordini aggiornati con successo'
        ]);
        exit;
    }
}