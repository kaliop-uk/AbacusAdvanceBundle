<?php


namespace Abacus\AdvanceBundle\Core\Response;

use Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException;
use Abacus\AdvanceBundle\Core\Entity\AvailableProduct;

class AvailableProductsResponse extends AdvanceResponse
{
    protected $userProductModelList;

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
     * @return AvailableProduct[]
     * @throws AdvanceResponseException
     */
    public function getProductList()
    {
        if ($this->userProductModelList === null) {
            $this->userProductModelList = [];
            $rawProducts = $this->getRawProductList();
            foreach ($rawProducts as $rawProduct) {
                $this->userProductModelList[] = (new AvailableProduct($rawProduct));
            }
        }
        return $this->userProductModelList;
    }
}
