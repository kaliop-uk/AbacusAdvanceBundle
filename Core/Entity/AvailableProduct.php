<?php

namespace Abacus\AdvanceBundle\Core\Entity;

/**
 * The data that represents a Product available to a user
 *
 * @property-read array $userProductModel
 * @property-read $orderDate
 * @property-read $productId
 * @property-read $qualificationType
 * @property-read $value
 * @property-read $isMetered
 * @property-read $meteredOrder
 * @property-read $accessFrequency
 * @property-read $formCoreMatrixID
 * @property-read $dataFilterStatus
 * @property-read $dataFilterId
 * @property-read $uRLRule
 */
class AvailableProduct extends ValueObject
{
    protected $userProductModel;
    protected $orderDate;
    protected $productId;
    protected $qualificationType;
    protected $value;
    protected $isMetered;
    protected $meteredOrder;
    protected $accessFrequency;
    protected $formCoreMatrixID;
    protected $dataFilterStatus;
    protected $dataFilterId;
    protected $uRLRule;
}