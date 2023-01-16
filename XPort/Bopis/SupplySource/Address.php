<?php

namespace XPort\Bopis\SupplySource;

use DomainException;

class Address
{
    /** @var string|null  */
    private ?string $name;

    /** @var string  */
    private string $addressLine1;

    /** @var string|null */
    private ?string $addressLine2;

    /** @var string  */
    private string $city;

    /** @var string  */
    private string $postalCode;

    /** @var string  */
    private string $countryCode;

    /**
     * @param array $addressData
     * @throws DomainException se uno dei campi obbligatori non è definito
     */
    public function __construct(array $addressData)
    {
        $requiredFields = ['addressLine1', 'city'];
        foreach($requiredFields as $fieldName) {
            if(!isset($addressData[$fieldName])) {
                throw new DomainException("Il campo '$fieldName' è obbligatorio");
            }
        }

        $fields = [
            'name', 'addressLine1', 'addressLine2', 'city', 'postalCode', 'countryCode' ];
        foreach($fields as $fieldName) {
            $this->$fieldName = isset($addressData[$fieldName]) ? $addressData[$fieldName] : "";
        }
    }

    public function __toString()
    {
        $addressLine = $this->addressLine1.($this->addressLine2 ? ' ' . $this->addressLine2 : '').($this->addressLine3 ? ' ' . $this->addressLine3 : '');
        $cityDetails = $this->postalCode . ' ' . $this->city.($this->district||$this->county ? " (" . implode(', ', [$this->district, $this->county ]) . ")" : '');

        return ($this->name ? $this->name . ', ' : '') .  $addressLine . ' --- ' . $cityDetails  . ', ' . $this->stateOrRegion . "(" . $this->countryCode . ")";
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $fields = [
            'name', 'addressLine1', 'addressLine2', 'addressLine3', 'city', 'county', 'district', 'stateOrRegion',
            'postalCode', 'countryCode' ];
        $data = [];
        foreach($fields as $field) {
            if($this->$field) {
                $data[$field]=$this->$field;
            }
        }

        return $data;
    }

}