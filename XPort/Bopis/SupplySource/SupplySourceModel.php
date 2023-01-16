<?php

namespace XPort\Bopis\SupplySource;

class SupplySourceModel
{
    /** @var string|null */
    private ?string $supplySourceId;

    /** @var string  */
    private string $supplySourceCode;

    /** @var string  */
    private string $alias;

    /** @var Address */
    private Address $address;

    /** @var string|null */
    private ?string $email;

    /** @var string|null */
    private ?string $phone;

    /** @var string|null */
    private ?string $timezone;

    /** @var OperatingHours|null */
    private ?OperatingHours $operatingHours;

    /** @var int|null Il tempo di gestione dell'ordine */
    private ?int $handlingTime;

    /** @var int|null Tempo di rimanenza in magazzino */
    private ?int $inventoryHoldPeriod;

    /**
     * @param array $storeData Un array del tipo
     * [
     *   supplySourceId => ..., supplySourceCode => .., alias =>..., address => [ .... ], ...
     *   operatinHours => ...
     * ]
     * ove il formato dell'array corrispondente ad details è quello necessario per costruire un Address
     * @throws DomainException Se il formato dell'array non è corretto
     */
    public function __construct(array $storeData)
    {
        if(!isset($storeData['supplySourceCode']) ||  !isset($storeData['alias']) || !isset($storeData['address']) ) {
            throw new \DomainException("Il formato di '" . json_encode($storeData) . "' è errato");
        }

        $this->supplySourceId = $storeData['supplySourceId'] ?? null;
        $this->supplySourceCode = $storeData['supplySourceCode'];
        $this->alias = $storeData['alias'];
        $addressData = $storeData['address'];
        $this->address = new Address($addressData);
        $this->email = $storeData['email'] ?? null;
        $this->phone = $storeData['phone'] ?? null;
        $this->timezone = $storeData['timezone'] ?? null;
        $this->handlingTime = $storeData['handlingTime'] ?? null;
        $this->inventoryHoldPeriod = $storeData['inventoryHoldPeriod'] ?? null;

        $operatingHours = $storeData['operatingHours'] ?? [];
        $operatingHours = array_filter($operatingHours, function($dayTimes) { return isset($dayTimes['startTime']) && isset($dayTimes['endTime']) && $dayTimes['startTime'] && $dayTimes['endTime']; });
        $this->operatingHours = new OperatingHours($operatingHours);
    }

    /**
     * @return string|null
     */
    public function getSupplySourceId(): ?string
    {
        return $this->supplySourceId;
    }

    /**
     * @param string|null $supplySourceId
     */
    public function setSupplySourceId(?string $supplySourceId): void
    {
        $this->supplySourceId = $supplySourceId;
    }

    /**
     * @return string
     */
    public function getSupplySourceCode(): string
    {
        return $this->supplySourceCode;
    }

    /**
     * @param string $supplySourceCode
     */
    public function setSupplySourceCode(string $supplySourceCode): void
    {
        $this->supplySourceCode = $supplySourceCode;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string|null $timezone
     */
    public function setTimezone(?string $timezone): void
    {
        $this->timezone = $timezone;
    }

    /**
     * @return OperatingHours|null
     */
    public function getOperatingHours(): ?OperatingHours
    {
        return $this->operatingHours;
    }

    /**
     * @param OperatingHours|null $operatingHours
     */
    public function setOperatingHours(?OperatingHours $operatingHours): void
    {
        $this->operatingHours = $operatingHours;
    }

    /**
     * @return int|null
     */
    public function getHandlingTime(): ?int
    {
        return $this->handlingTime;
    }

    /**
     * @param int|null $handlingTime
     */
    public function setHandlingTime(?int $handlingTime): void
    {
        $this->handlingTime = $handlingTime;
    }

    /**
     * @return int|null
     */
    public function getInventoryHoldPeriod(): ?int
    {
        return $this->inventoryHoldPeriod;
    }

    /**
     * @param int|null $inventoryHoldPeriod
     */
    public function setInventoryHoldPeriod(?int $inventoryHoldPeriod): void
    {
        $this->inventoryHoldPeriod = $inventoryHoldPeriod;
    }

    /**
     * @return array
     */
    public function toArray() :array
    {
        $fields = [ 'supplySourceId', 'supplySourceCode', 'alias', 'timezone', 'handlingTime', 'inventoryHoldPeriod'];
        $data = [];
        foreach($fields as $field) {
            if($this->$field) {
                $data[$field]=$this->$field;
            }
        }
        $data['address'] = $this->getAddress()->toArray();
        $data['operatingHours'] = $this->getOperatingHours()->toArray();

        return $data;
    }


}