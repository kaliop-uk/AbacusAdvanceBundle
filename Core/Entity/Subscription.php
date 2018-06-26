<?php

namespace Abacus\AdvanceBundle\Core\Entity;

/**
 * Used to build an instance of Subscription, based on UserSubscriptionsResponse
 * @see \Abacus\AdvanceBundle\Core\Response\UserSubscriptionsResponse
 *
 * @property-read $subscriptionID
 * @property-read $startDate
 * @property-read $startDateTime
 * @property-read $endDate
 * @property-read $endDateTime
 * @property-read $productID
 * @property-read $productName
 * @property-read $productCode
 * @property-read $productType
 * @property-read $orderCode
 * @property-read $orderDate
 * @property-read $subscriptionType
 * @property-read $orderStatus
 * @property-read $subscriptionStatus
 * @property-read $orderStatusDescription
 * @property-read $subscriptionPrice
 * @property-read $deliveryAddress
 * @property-read $emailAccount
 */
class Subscription extends ValueObject
{
    protected $subscriptionID;
    protected $startDate;
    protected $startDateTime;
    protected $endDate;
    protected $endDateTime;
    protected $productID;
    protected $productName;
    protected $productCode;
    protected $productType;
    protected $orderCode;
    protected $orderDate;
    protected $subscriptionType;
    protected $orderStatus;
    protected $subscriptionStatus;
    protected $orderStatusDescription;
    protected $subscriptionPrice;
    protected $deliveryAddress;
    protected $emailAccount;
}
