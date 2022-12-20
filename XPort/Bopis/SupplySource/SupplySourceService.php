<?php

namespace XPort\Bopis\SupplySource;

use DomainException;
use GuzzleHttp\ClientInterface;
use XPort\Bopis\AbstractService;
use XPort\Bopis\BopisCommonService;

class SupplySourceService
{
    const API_BASE_URL = 'supplysource';

    private ClientInterface $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Ritorna gli store registrati o null in caso di errore
     *
     * @return array|null
     */
    public function getAll() :?array
    {
        $url = BopisCommonService::buildUrl(self::API_BASE_URL . '/');
        $response = BopisCommonService::request($this->client, 'GET', $url);

        if($response === null || !isset($response['supplySources'])) {
            return null;
        }

        $answer = [];
        try {
            foreach ($response['supplySources'] as $storeData) {
                $answer[] = new SupplySourceModel($storeData);
            }
        }
        catch(DomainException $e) {
            echo $e->getMessage();
            return null;
        }

        return $answer;
    }

    /**
     * Ritorna lo store di dato alias, o null se non esiste
     *
     * @param string $alias
     * @return SupplySourceModel|null
     */
    public function getByAlias(string $alias):?SupplySourceModel
    {
        $stores = $this->getAll();
        foreach ($stores as $store) {
            if($store->getAlias() == $alias) {
                return $store;
            }
        }

        return null;
    }

    /**
     * Ritorna lo store di dato id o null se tale store non esiste.
     *
     * @param string $storeId
     * @return SupplySourceModel|null
     */
    public function get(string $storeId) :?SupplySourceModel
    {
        $url = BopisCommonService::buildUrl(self::API_BASE_URL . '/' . $storeId);
        $response = BopisCommonService::request($this->client, 'GET', $url);

        if($response === null) {
            return null;
        }

        try {
            $store = new SupplySourceModel($response);
        }
        catch(DomainException $e) {
            return null;
        }

        return $store;
    }

    /**
     * Cre lo store a partire dai campi specificati e ritorna lo storeId corrispondente, o null in caso di errore
     *
     * @param SupplySourceModel $store
     * @return bool True in caso di successo, false in caso di errore
     */
    public function create(SupplySourceModel $store) :bool
    {
        $url = BopisCommonService::buildUrl(self::API_BASE_URL . '/');

        $data = $store->toArray();

        $response = BopisCommonService::request($this->client, 'POST', $url, $data);

        var_dump($response);

        if($response == null) {
            return false;
        }

        return true;
    }
}