<?php

namespace XPort\Bopis\Order;

class Customer
{
    /** @var string  */
    private string $email;

    /** @var string|null  */
    private ?string $firstName;


    /** @var string|null  */
    private ?string $lastName;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? null;
        $this->firstName = $data['firstName'] ?? null;
        $this->lastName = $data['lastName'] ?? null;
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /** @var string */
    public function getFullIdentity()
    {
        $fullName = ($this->getFirstName() ?? '') . ($this->getLastName() ?? '');
        return ($fullName ? $fullName . " - " : "") . $this->getEmail();
    }



}