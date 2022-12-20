<?php 

use XPort\Auth;
use XPort\Mvc\FrontController;
use XPort\Mvc\MvcException;

include 'vendor/autoload.php';
include 'config.php';

session_start();

$client = new GuzzleHttp\Client();
$service = new \XPort\Bopis\SupplySource\SupplySourceService($client);

$address = new \XPort\Bopis\SupplySource\Address([
    "name" => 'Angelino De Angeli',
    "addressLine1" => "Strada dei 100 Anni 12123123121",
    "city" => "Washington",
    "stateOrRegion" => "PD",
    "postalCode" => "30039",
    "countryCode" => "IT"
]);
$store = new \XPort\Bopis\SupplySource\SupplySourceModel([
    'alias' => 'xxadsasadadsdssa',
    'supplySourceCode' => 'asasddasasa',
    'address' => $address->toArray()
]);

var_dump($service->getAll());