<?php

namespace XPort\Mvc\Controller;

use GuzzleHttp\Client;

use GuzzleHttp\ClientInterface;
use XPort\Bopis\SupplySource\SupplySourceModel;
use XPort\Bopis\SupplySource\SupplySourceService;
use XPort\Mapper\OrderMapper;
use XPort\Mapper\StoreMapper;
use XPort\Mvc\AbstractController;

class Store extends AbstractController
{

    public function index()
    {
        $client = new Client();
        $service = new SupplySourceService($client);

        if($service->isSomeStoreRegistered()) {
            header("Location: /store/save");
        }
        else {
            header("Location: /store/create");
        }
        exit;
    }

    public function create()
    {
        if($_POST) {
            $fields = [
                'alias', 'supplySourceCode','email'
            ];
            $addressFields = [
                'addressLine1', 'addressLine2', 'addressLine3', 'city', 'county', 'district',
                'stateOrRegion', 'postalCode', 'countryCode', 'email','phone'
            ];
            $storeData = [];
            foreach($fields as $field) {
                $storeData[$field] = $_POST[$field] ?? '';
            }
            foreach($addressFields as $field) {
                $storeData['address'][$field] = $_POST[$field] ?? '';
            }
            // HACK
            $storeData['supplySourceCode']=$storeData['alias'];
            // END OF HACK
            $store = new SupplySourceModel($storeData);


            $client = new Client();
            $service = new SupplySourceService($client);

            $response = $service->create($store);
            var_dump($response);exit;

            header("location: /store");
            exit;
        }

        echo $this->getRenderer()->render('store/create');
    }
    
    public function save()
    {
        echo "UPDATE";exit;

        $mapper = new StoreMapper();

        if($_POST) {
            $fields = [
                'alias', 'addressline1', 'addressline2', 'addressline3', 'city', 'county', 'district',
                'state_or_region', 'postal_code', 'country_code', 'supply_source_code','email','phone',
                'monday_start','monday_end','tuesday_start','tuesday_end','wednesday_start','wednesday_end','thursday_start',
                'thursday_end', 'friday_start','friday_end','saturday_start','saturday_end','sunday_start','sunday_end'
            ];
            $store = [];
            foreach($fields as $field) {
                $store[$field] = $_POST[$field] ?? '';
            }

            $mapper->save($store);
            header("location: /store");
            exit;
        }

        $store = $mapper->load();


        echo $this->getRenderer()->render('store/index',['store' => $store]);
    }

}