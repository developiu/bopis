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