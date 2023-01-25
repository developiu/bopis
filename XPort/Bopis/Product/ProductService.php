<?php

namespace XPort\Bopis\Product;

use DomainException;
use GuzzleHttp\ClientInterface;
use XPort\Bopis\AbstractService;
use XPort\Bopis\BopisCommonService;
use XPort\Bopis\SupplySource\SupplySourceModel;

class ProductService
{
    const API_BASE_URL = 'items';

    private ClientInterface $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Aggiorna la quantità del prodotto di dato SKU. Ritorna true in caso di successo e false altrimenti
     *
     * @param string $productSKU
     * @param int $newQuantity
     * @return bool
     */
    public function updateQuantity(string $productSKU, int $newQuantity) :bool
    {
        $url = BopisCommonService::buildUrl(self::API_BASE_URL . '/' . $productSKU);
        $inputData = [
            [
                "fulfillment_channel_code" => 'DEFAULT',
                "quantity" => $newQuantity
            ]
        ];
        $response = BopisCommonService::request($this->client, 'PATCH', $url, $inputData);
        if($response === null || $response['status']!="ACCEPTED") {
            return false;
        }
        return true;
    }

}