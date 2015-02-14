<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace HQTest\PaymentLibBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Description of orders
 * @MongoDB\Document
 * @MongoDB\HasLifecycleCallbacks()
 * @author zyko
 */
class Orders {

    /**
     * * @MongoDB\Id(strategy="auto")
     */
    protected $ID;
     /**
     * * @MongoDB\int
     */
    protected $orderID;

    /**
     * @MongoDB\string
     */
    protected $customerName;

    /**
     * @MongoDB\string
     */
    protected $cardHolderName;

    /**
     * @MongoDB\string
     */
    protected $cardNumber;

    /**
     * @MongoDB\string
     */
    protected $cardType;

    /**
     * @MongoDB\string
     */
    protected $cardExpiry;

    /**
     * @MongoDB\string
     */
    protected $cardCvv;

    /**
     * @MongoDB\string
     */
    protected $paymentCurrency;

    /**
     * @MongoDB\string
     */
    protected $paymentGateway;

    /**
     * @MongoDB\string
     */
    protected $transactionId;

    /**
     * @MongoDB\string
     */
    protected $orderStatus;

    /**
     * @MongoDB\string
     */
    protected $orderAmount;

    /**
     * @MongoDB\date
     */
    protected $orderCreatedTime;

    /**
     * @MongoDB\string
     */
    protected $transactionResponse;


    /**
     * Get orderID
     *
     * @return id $orderID
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     * @return self
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * Get customerName
     *
     * @return string $customerName
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set cardHolderName
     *
     * @param string $cardHolderName
     * @return self
     */
    public function setCardHolderName($cardHolderName)
    {
        $this->cardHolderName = $cardHolderName;
        return $this;
    }

    /**
     * Get cardHolderName
     *
     * @return string $cardHolderName
     */
    public function getCardHolderName()
    {
        return $this->cardHolderName;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     * @return self
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string $cardNumber
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set cardType
     *
     * @param string $cardType
     * @return self
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }

    /**
     * Get cardType
     *
     * @return string $cardType
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Set cardExpiry
     *
     * @param string $cardExpiry
     * @return self
     */
    public function setCardExpiry($cardExpiry)
    {
        $this->cardExpiry = $cardExpiry;
        return $this;
    }

    /**
     * Get cardExpiry
     *
     * @return string $cardExpiry
     */
    public function getCardExpiry()
    {
        return $this->cardExpiry;
    }

    /**
     * Set cardCvv
     *
     * @param string $cardCvv
     * @return self
     */
    public function setCardCvv($cardCvv)
    {
        $this->cardCvv = $cardCvv;
        return $this;
    }

    /**
     * Get cardCvv
     *
     * @return string $cardCvv
     */
    public function getCardCvv()
    {
        return $this->cardCvv;
    }

    /**
     * Set paymentCurrency
     *
     * @param string $paymentCurrency
     * @return self
     */
    public function setPaymentCurrency($paymentCurrency)
    {
        $this->paymentCurrency = $paymentCurrency;
        return $this;
    }

    /**
     * Get paymentCurrency
     *
     * @return string $paymentCurrency
     */
    public function getPaymentCurrency()
    {
        return $this->paymentCurrency;
    }

    /**
     * Set paymentGateway
     *
     * @param string $paymentGateway
     * @return self
     */
    public function setPaymentGateway($paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
        return $this;
    }

    /**
     * Get paymentGateway
     *
     * @return string $paymentGateway
     */
    public function getPaymentGateway()
    {
        return $this->paymentGateway;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     * @return self
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string $transactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set orderStatus
     *
     * @param string $orderStatus
     * @return self
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return string $orderStatus
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set orderAmount
     *
     * @param string $orderAmount
     * @return self
     */
    public function setOrderAmount($orderAmount)
    {
        $this->orderAmount = $orderAmount;
        return $this;
    }

    /**
     * Get orderAmount
     *
     * @return string $orderAmount
     */
    public function getOrderAmount()
    {
        return $this->orderAmount;
    }

    /**
     * Set orderCreatedTime
     *
     * @MongoDB\PrePersist
     * @param string $orderCreatedTime
     * @return self
     */
    public function setOrderCreatedTime()
    {
        $this->orderCreatedTime = new \DateTime();
        return $this;
    }

    /**
     * Get orderCreatedTime
     *
     * @return string $orderCreatedTime
     */
    public function getOrderCreatedTime()
    {
        return $this->orderCreatedTime;
    }

    /**
     * Set transactionResponse
     *
     * @param string $transactionResponse
     * @return self
     */
    public function setTransactionResponse($transactionResponse)
    {
        $this->transactionResponse = $transactionResponse;
        return $this;
    }

    /**
     * Get transactionResponse
     *
     * @return string $transactionResponse
     */
    public function getTransactionResponse()
    {
        return $this->transactionResponse;
    }

    /**
     * Get iD
     *
     * @return id $iD
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * Set orderID
     *
     * @param int $orderID
     * @return self
     */
    public function setOrderID($orderID)
    {
        $this->orderID = $orderID;
        return $this;
    }
}
