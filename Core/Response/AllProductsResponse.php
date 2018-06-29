<?php


namespace Abacus\AdvanceBundle\Core\Response;

use Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException;
use Abacus\AdvanceBundle\Core\Entity\Product;

class AllProductsResponse extends AdvanceResponse
{
    protected $productList;

    /**
     * @inheritdoc
     */
    protected function validateResponseFormat()
    {
        $data = $this->getData();
        return
            isset($data['list']) &&
            is_array($data['list'])
            ;
    }

    /**
     * @return array
     * @throws AdvanceResponseException
     * @todo move the exception throwing to validateResponseFormat...
     */
    public function getRawProductList()
    {
        if ($this->isValidResponse()) {
            return $this->getData()['list'];
        } else {
            throw new AdvanceResponseException('Invalid Response from remote service');
        }
    }

    /**
     * @return Product[]
     * @throws AdvanceResponseException
     */
    public function getProductList()
    {
        if ($this->productList === null) {
            $this->productList = [];
            $rawProducts = $this->getRawProductList();
            foreach ($rawProducts as $rawProduct) {
                $this->productList[] = new Product($rawProduct);
            }
        }
        return $this->productList;
    }
}
