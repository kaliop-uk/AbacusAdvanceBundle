<?php

namespace Abacus\AdvanceBundle\Core\Service\StubDataProvider;

use Abacus\AdvanceBundle\Core\Response\AvailableProductsResponse;
use Abacus\AdvanceBundle\Core\Response\BelongToBIResponse;
use Abacus\AdvanceBundle\Core\Response\UserDetailsResponse;
use Abacus\AdvanceBundle\Core\Response\UserSubscriptionsResponse;
use Abacus\AdvanceBundle\Core\Response\WebActivityResponse;

class User extends Base
{
    public function details(
        $userToken = null
    )
    {
        $profile = $this->getUserProfileFromToken($userToken);

        if (!$profile) {
            return $this->failedResponse();
        }

        return new UserDetailsResponse($this->buildResponseArray(
            [
                'Person' => [
                    'UserRegistrationID' => $profile['UserRegistrationID'],
                    'Forname' => $profile['Forname'],
                    'Surname' => $profile['Surname'],
                    'Salutation' => $profile['Salutation'],
                    'Gender' => $profile['Gender'],
                    'DateOfBirthday' => $profile['DateOfBirthday'],
                    'UserToken' => null /// @todo
                ]
            ]
        ));
    }

    public function getUserSubscriptions(
        $userToken = null,
        $ipAddress = null
    )
    {
        $profile = $this->getUserProfileFromToken($userToken);

        if (!$profile) {
            return $this->failedResponse();
        }

        /// @todo
        return new UserSubscriptionsResponse($this->buildResponseArray(
            $profile['Subscriptions']
            /*[
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
            ]*/
        ));
    }

    public function getListOfAvailableProducts(
        $userToken = null,
        $partyId = null,
        $brandId = null,
        $ipAddress = null
    )
    {
        $profile = $this->getUserProfileFromToken($userToken);

        if (!$profile) {
            return $this->failedResponse();
        }

        return new AvailableProductsResponse($this->buildResponseArray(
            $profile['AvailableProducts']
            /*[
                'list' => []
            ]*/
        ));
    }

    public function doesPartyBelongToBI(
        $userToken = null
    )
    {
        $profile = $this->getUserProfileFromToken($userToken);

        if (!$profile) {
            return $this->failedResponse();
        }

        return new BelongToBIResponse($this->buildResponseArray(
            $profile['BIRulesBelongedTo']
            /*[
                'BIResult' => [
                    'Status' => null
                ]
            ]*/
        ));
    }

    public function logWebActivity(
        $userToken = null
    )
    {
        $profile = $this->getUserProfileFromToken($userToken);

        if (!$profile) {
            return $this->failedResponse();
        }

        return new WebActivityResponse($this->buildResponseArray(
           []
        ));
    }
}
