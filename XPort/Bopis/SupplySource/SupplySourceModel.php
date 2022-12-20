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

    /**
     * @param array $storeData Un array del tipo [ supplySourceId => ..., supplySourceCode => .., alias =>..., details => [ .... ] ]
     * ove il formato dell'array corrispondente ad details è quello necessario per costruire un Address
     * @throws DomainException Se il formato dell'array non è corretto
     */
    public function __construct(array $storeData)
    {
        if(!isset($storeData['supplySourceCode']) ||  !isset($storeData['alias']) || (!isset($storeData['details'])  && !isset($storeData['address']))  ) {
            throw new \DomainException("Il formato di '" . json_encode($storeData) . "' è errato");
        }

        $this->supplySourceId = $storeData['supplySourceId'] ?? null;
        $this->supplySourceCode = $storeData['supplySourceCode'];
        $this->alias = $storeData['alias'];
        $addressData = $storeData['details'] ?? $storeData['address'];
        $this->address = new Address($addressData);
    }

    /**
     * @return string
     */
    public function getSupplySourceId(): string
    {
        return $this->supplySourceId;
    }

    /**
     * @param string $supplySourceId
     */
    public function setSupplySourceId(string $supplySourceId): void
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
     * @return array
     */
    public function toArray() :array
    {
        $fields = [ 'supplySourceId', 'supplySourceCode', 'alias'];
        $data = [];
        foreach($fields as $field) {
            if($this->$field) {
                $data[$field]=$this->$field;
            }
        }
        $data['address'] = $this->getAddress()->toArray();

        return $data;
    }


}