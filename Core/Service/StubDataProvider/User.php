<?php

namespace Abacus\AdvanceBundle\Core\Service\StubDataProvider;

use Abacus\AdvanceBundle\Core\Response\AvailableProductsResponse;
use Abacus\AdvanceBundle\Core\Response\BelongToBIResponse;
use Abacus\AdvanceBundle\Core\Response\UserDetailsResponse;
use Abacus\AdvanceBundle\Core\Response\UserSubscriptionsResponse;
use Abacus\AdvanceBundle\Core\Response\WebActivityResponse;

class User extends Base
{
    public function details()
    {
        /// @todo
        return new UserDetailsResponse($this->buildResponseArray(
            [
                'Person' => [
                    'Forname' => null,
                    'Surname' => null
                ]
            ]
        ));
    }

    public function getUserSubscriptions()
    {
        /// @todo
        return new UserSubscriptionsResponse($this->buildResponseArray(
            [
                'list' => [
                    [
                        'productCode' => 'LIP',
                        'subscriptionStatus' => 'Live'
                    ],
                    [
                        'productCode' => 'LIS',
                        'subscriptionStatus' => 'Live'
                    ],
                    [
                        'productCode' => 'LISN',
                        'subscriptionStatus' => 'Live'
                    ]
                ]
            ]
        ));
    }

    public function getListOfAvailableProducts()
    {
        /// @todo
        return new AvailableProductsResponse($this->buildResponseArray(
            [
                'list' => []
            ]
        ));
    }

    public function doesPartyBelongToBI()
    {
        /// @todo
        return new BelongToBIResponse($this->buildResponseArray(
            [
                'BIResult' => [
                    'Status' => null
                ]
            ]
        ));
    }

    public function logWebActivity()
    {
        return new WebActivityResponse($this->buildResponseArray(
           []
        ));
    }
}
