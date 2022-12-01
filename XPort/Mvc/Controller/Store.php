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
            $fields = [
                'alias', 'addressline1', 'addressline2', 'addressline3', 'city', 'county', 'district',
                'state_or_region', 'postal_code', 'country_code', 'supply_source_code'
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