<?php

namespace XPort\Bopis\SupplySource;

use DomainException;

class Address
{
    /** @var string  */
    private string $addressLine1;

    /** @var string|null */
    private ?string $addressLine2;

    /** @var string|null */
    private ?string $addressLine3;

    /** @var string  */
    private string $city;

    /** @var string|null */
    private ?string $county;

    /** @var string|null  */
    private ?string $district;

    /** @var string */
    private string $stateOrRegion;

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
        $requiredFields = ['addressLine1', 'city', 'stateOrRegion', 'postalCode', 'countryCode'];
        foreach($requiredFields as $fieldName) {
            if(!isset($addressData[$fieldName])) {
                throw new DomainException("Il campo '$fieldName' è obbligatorio");
            }
        }

        $fields = [
            'addressLine1', 'addressLine2', 'addressLine3', 'city', 'county', 'district', 'stateOrRegion',
            'postalCode', 'countryCode' ];
        foreach($fields as $fieldName) {
            $this->$fieldName = (isset($addressData[$fieldName]) && $addressData[$fieldName]) ? $addressData[$fieldName] : null;
        }
    }

    public function __toString()
    {
        $addressLine = $this->addressLine1.($this->addressLine2 ? ' ' . $this->addressLine2 : '').($this->addressLine3 ? ' ' . $this->addressLine3 : '');
        $cityDetails = $this->postalCode . ' ' . $this->city.($this->district||$this->county ? " (" . implode(', ', [$this->district, $this->county ]) . ")" : '');

        return $addressLine . ' --- ' . $cityDetails  . ', ' . $this->stateOrRegion . "(" . $this->countryCode . ")";
    }
    
}