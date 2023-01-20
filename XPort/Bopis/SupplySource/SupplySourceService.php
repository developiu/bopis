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
     * @return SupplySourceModel[]|null
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
                $storeData['address'] = $storeData['details'];
                unset($storeData['details']);
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
     * Ritorna true se esiste almeno uno store registrato, false altrimenti.
     *
     * @return bool
     */
    public function isSomeStoreRegistered(): bool
    {
        return !empty($this->getAll());
    }

    /**
     * Ritorna lo store di dato id o null se tale store non esiste. Attenzione,
     * questa chiamata ritorna tutti i dati, mentre getAll ne ritorna solo alcuni.
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
            $store = self::createFromGetData($response);
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
     * @return bool True in caso di successo, false in caso di errore.
     */
    public function create(SupplySourceModel $store) :bool
    {
        $url = BopisCommonService::buildUrl(self::API_BASE_URL . '/');

        $data = self::getCreateData($store);

        $response = BopisCommonService::request($this->client, 'POST', $url, $data);

        if($response === null) {
            return false;
        }

        return true;
    }

    /**
     * Aggiorna lo store con i nuovi dati. Lo store da modificare viene trovato usando l'id di $store
     *
     * @param SupplySourceModel $store
     * @return bool
     * @throws DomainException se $store non ha la supplySourceId definita
     */
    public function update(SupplySourceModel $store) :bool
    {
        if($store->getSupplySourceId()===null) {
            throw new DomainException("Non è possibile aggiornare uno store se non ha la supplySourceId definita");
        }

        $url = BopisCommonService::buildUrl(self::API_BASE_URL . '/' . $store->getSupplySourceId());

        $data = self::getUpdateData($store);

        $response = BopisCommonService::request($this->client, 'PUT', $url, $data);

        if($response == null) {
            return false;
        }

        return $response;
    }

    private static function getCreateData(SupplySourceModel $store): array
    {
        return [
            "supplySourceCode" => $store->getSupplySourceCode(),
            "alias" => $store->getAlias(),
            "address" => $store->getAddress()->toArray()
        ];
    }

    public static function getUpdateData(SupplySourceModel $store): array
    {
        $data = [
            "alias" =>  $store->getAlias(),
            "configuration" =>  [
                "operationalConfiguration" =>  [
                    "contactDetails" =>  [
                        "primary" =>  [
                            "email" =>  $store->getEmail(),
                            "phone" =>  $store->getPhone()
                        ]
                    ],
                    "operatingHoursByDay" =>  $store->getOperatingHours()->toArray(),
                    "throughputConfig" =>  [
                        "throughputCap" =>  [
                            "value" =>  5,
                            "timeUnit" =>  "Hours"
                        ]
                    ]
                ],
                "timezone" =>  $store->getTimezone(),
                "handlingTime" =>  [
                    "value" =>  $store->getHandlingTime(),
                    "timeUnit" =>  "Hours"
                ]
            ],
            "capabilities" =>  [
                "outbound" =>  [
                    "isSupported" =>  true,
                    "operationalConfiguration" =>  [
                        "contactDetails" =>  [
                            "primary" =>  [
                                "email" =>  $store->getEmail(),
                                "phone" =>  $store->getPhone()
                            ]
                        ],
                        "operatingHoursByDay" =>  $store->getOperatingHours()->toArray(),
                        "throughputConfig" =>  [
                            "throughputCap" =>  [
                                "value" =>  5,
                                "timeUnit" =>  "Hours"
                            ]
                        ]
                    ],
                    "returnLocation" =>  [
                        "addressWithContact" =>  [
                            "address" =>  $store->getAddress()->toArray(),
                            "contactDetails" =>  [
                                "primary" =>  [
                                    "email" =>  $store->getEmail(),
                                    "phone" =>  $store->getPhone()
                                ]
                            ]
                        ]
                    ],
                    "deliveryChannel" =>  [
                        "isSupported" =>  true,
                        "operationalConfiguration" =>  [
                            "contactDetails" =>  [
                                "primary" =>  [
                                    "email" =>  $store->getEmail(),
                                    "phone" =>  $store->getPhone()
                                ]
                            ],
                            "operatingHoursByDay" =>  $store->getOperatingHours()->toArray(),
                            "throughputConfig" =>  [
                                "throughputCap" =>  [
                                    "value" =>  5,
                                    "timeUnit" =>  "Hours"
                                ]
                            ]
                        ]
                    ],
                    "pickupChannel" =>  [
                        "isSupported" =>  true,
                        "inventoryHoldPeriod" =>  [
                            "value" =>  $store->getInventoryHoldPeriod(),
                            "timeUnit" =>  "Days"
                        ],
                        "operationalConfiguration" =>  [
                            "contactDetails" =>  [
                                "primary" =>  [
                                    "email" =>  $store->getEmail(),
                                    "phone" =>  $store->getPhone()
                                ]
                            ],
                            "operatingHoursByDay" =>  $store->getOperatingHours()->toArray(),
                            "throughputConfig" =>  [
                                "throughputCap" =>  [
                                    "value" =>  5,
                                    "timeUnit" => "Hours"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $data;
    }

    /**
     * Crea uno store a partire dai dati ritornati dalla chiamata a GET
     *
     * @param $data
     * @return SupplySourceModel
     */
    private function createFromGetData($data): SupplySourceModel
    {
        $data['email'] = $data['configuration']['operationalConfiguration']['contactDetails']['primary']['email'];
        $data['phone'] = $data['configuration']['operationalConfiguration']['contactDetails']['primary']['phone'];
        $data['timezone'] = $data['configuration']['timezone'];
        $data['handlingTime'] = $data['capabilities']['outbound']['pickupChannel']['operationalConfiguration']['handlingTime']['value'];
        $data['inventoryHoldPeriod'] = $data['capabilities']['outbound']['pickupChannel']['inventoryHoldPeriod']['value'];
        $data['operatingHours'] = [];

        foreach($data['configuration']['operationalConfiguration']['operatingHoursByDay'] as $day=>$timeIntervals) {
            if(empty($timeIntervals)) {
                continue;
            }
            $data['operatingHours'][$day] = array_shift($timeIntervals);
        }

        unset($data['configuration']);
        unset($data['capabilities']);

        return new SupplySourceModel($data);
    }

}