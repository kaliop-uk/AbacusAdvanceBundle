<?php

namespace Abacus\AdvanceBundle\Core\Entity;

/**
 * @property-read $productId
 * @property-read $productName
 * @property-read $productType
 * @property-read $brandId
 * @property-read $brandName
 * @property-read $productCode
 * @property-read $description
 * @property-read $recordStatus
 */
class Product extends ValueObject
{
    protected $productId;
    protected $productName;
    protected $productType;
    protected $brandId;
    protected $brandName;
    protected $productCode;
    protected $description;
    protected $recordStatus;
}