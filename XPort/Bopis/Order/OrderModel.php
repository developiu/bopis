<?php

namespace XPort\Bopis\Order;

use XPort\Bopis\SupplySource\DomainException;
class OrderModel
{
    
    /** @var string */
    private string $id;

    /** @var \DateTime  */
    private \DateTime $creationDate;

    /** @var Customer  */
    private Customer $customer;

    /** @var float  */
    private float $amount;

    /** @var string  */
    private string $status;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param \DateTime $creationDate
     */
    public function setCreationDate(\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     */
    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * Prende come argomento un array con i dati ritornati dalla API e ritorna un corrispondente OrderModel.
     *
     * @param array $data
     * @return OrderModel
     * @throws \DomainException se $data non contiene i dati necessari
     */
    public static function createFromAmazonOder(array $data): OrderModel
    {
        if(!isset($data['order']['AmazonOrderId']) || !isset($data['order']['PurchaseDate']) ||
           !isset($data['order']['BuyerInfo']['BuyerEmail']) || !isset($data['order']['OrderTotal']['Amount']) ||
           !is_numeric($data['order']['OrderTotal']['Amount']) || !isset($data['order']['OrderStatus'])
        ) {
            throw new \DomainException("Alcuni campi della struttura ritornata dalla API sono mancanti");
        }
        try {
            $creationDate = new \DateTime($data['order']['PurchaseDate']);
        }
        catch(\Exception $e) {
            throw new \DomainException("Il formato della data di creazione non è corretto");
        }

        $orderModel = new self();
        $orderModel->setId($data['order']['AmazonOrderId']);
        $orderModel->setCreationDate($creationDate);

        $customerData = [
            'email' => $data['order']['BuyerInfo']['BuyerEmail'],
            'firstName' => $data['order']['BuyerInfo']['firstName'],
            'lastName' => $data['order']['BuyerInfo']['lastName']
        ];
        $orderModel->setCustomer(new Customer($customerData));
        $orderModel->setAmount(floatval($data['order']['OrderTotal']['Amount']));
        $orderModel->setStatus($data['order']['OrderStatus']);

        return $orderModel;
    }


}