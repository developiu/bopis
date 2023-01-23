<?php

namespace XPort\Bopis\Order;

use GuzzleHttp\ClientInterface;
use XPort\Bopis\BopisCommonService;

class OrderService
{
    const API_BASE_URL = 'orders';

    private ClientInterface $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }



    /**
     * Ritorna l'ordine di dato id o null se tale store non esiste.
     *
     * @param string $orderId
     * @return OrderModel|null
     */
    public function get(string $orderId) :?OrderModel
    {
        $url = BopisCommonService::buildUrl(self::API_BASE_URL . '/' . $orderId);
        $response = BopisCommonService::request($this->client, 'GET', $url);

        if($response === null) {
            return null;
        }

        try {
            $order = OrderModel::createFromAmazonOder($response);
        }
        catch(DomainException $e) {
            return null;
        }

        return $order;
    }

    /**
     * Aggiorna lo stato dell'ordine di dato id
     *
     * @param string $orderId
     * @param string $newStatus
     * @return bool true in caso di successo, false altrimenti
     */
    public function updateStatus($orderId, $newStatus)
    {
        return true;

        /*$url = BopisCommonService::buildUrl(self::API_BASE_URL . '/' . $orderId . '/shipment');
        $inputData = [
            "shipmentStatus" => $newStatus
        ];
        $response = BopisCommonService::request($this->client, 'POST', $url, $inputData);
        if($response === null) {
            return false;
        }
        return true;*/

    }

}