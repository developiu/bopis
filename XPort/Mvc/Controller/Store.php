<?php

namespace XPort\Mvc\Controller;

use GuzzleHttp\Client;

use XPort\Bopis\SupplySource\SupplySourceModel;
use XPort\Bopis\SupplySource\SupplySourceService;
use XPort\Mapper\StoreMapper;
use XPort\Mvc\AbstractController;

class Store extends AbstractController
{

    public function index()
    {
        $client = new Client();
        $service = new SupplySourceService($client);

        $registeredStores = $service->getAll();
        $firstStore = array_shift($registeredStores);
        $store = $service->get($firstStore->getSupplySourceId());

        if(!$store) {
            $store = new SupplySourceModel(['supplySourceCode' => '', 'alias' => '', 'address' => ['addressLine1' => '', 'city' => '' ]]);
        }

        echo $this->getRenderer()->render('store/index', ['store' => $store]);
    }

    public function save()
    {
       if($_POST) {
            $data = $_POST;
            if(!$data['supplySourceCode']) {
                $data['supplySourceCode'] = uniqid();
            }
            $store = new SupplySourceModel($data);

            $client = new Client();
            $service = new SupplySourceService($client);

            $successful = true;
            if(!$service->isSomeStoreRegistered()) { // new store creation
                $successful = $service->create($store);
            }

            if($successful) {
                $allStores = $service->getAll();
                $currentStoreOnApi = array_shift($allStores);
                $store->setSupplySourceId($currentStoreOnApi->getSupplySourceId());

                $successful = $service->update($store);
            }
       }
       header("Location: /store");
    }
    public function create()
    {
        if ($_POST) {
            $fields = [
                'alias', 'supplySourceCode', 'email'
            ];
            $addressFields = [
                'addressLine1', 'addressLine2', 'addressLine3', 'city', 'county', 'district',
                'stateOrRegion', 'postalCode', 'countryCode', 'email', 'phone'
            ];
            $storeData = [];
            foreach ($fields as $field) {
                $storeData[$field] = $_POST[$field] ?? '';
            }
            foreach ($addressFields as $field) {
                $storeData['address'][$field] = $_POST[$field] ?? '';
            }
            // HACK
            $storeData['supplySourceCode'] = $storeData['alias'];
            // END OF HACK
            $store = new SupplySourceModel($storeData);


            $client = new Client();
            $service = new SupplySourceService($client);

            $response = $service->create($store);
            var_dump($response);
            exit;

            header("location: /store");
            exit;
        }

        echo $this->getRenderer()->render('store/create');
    }

}