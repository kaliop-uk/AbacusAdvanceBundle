<?php

namespace Abacus\AdvanceBundle\Core\Service;

use Abacus\AdvanceBundle\Core\Response\WebActivityResponse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Abacus\AdvanceBundle\Core\AdvanceService;
use Abacus\AdvanceBundle\Core\Response\LoginResponse;
use Abacus\AdvanceBundle\Core\Response\UserDetailsResponse;
use Abacus\AdvanceBundle\Core\Response\UserSubscriptionsResponse;
use Abacus\AdvanceBundle\Core\Response\AvailableProductsResponse;
use Abacus\AdvanceBundle\Core\Response\BelongToBIResponse;

class User extends AdvanceService
{

    /**
     * Authenticate end users / subscribers with the system.
     *
     * @param array $options
     *
     * @return \Abacus\AdvanceBundle\Core\Response\LoginResponse
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function login($options)
    {
        $params = (new OptionsResolver())
            ->setRequired([
                'Username',
                'Password',
            ])
            ->setDefined([
                'concurrencyCookie',
                'ipAddress',
            ])
            ->resolve($options);

        /** @var \Abacus\AdvanceBundle\Core\Response\LoginResponse $response */
        $response =  $this->apiPost('Login/Login', $params, LoginResponse::class);
        return $response;
    }

    /**
     * Authenticate end users / subscribers with the system based on a frontend cookie (must use same domain as ADvance)
     *
     * @param array $options
     *
     * @return \Abacus\AdvanceBundle\Core\Response\LoginResponse
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function autoLogin($options)
    {
        $params = (new OptionsResolver())
            ->setRequired([
                'frontendCookie',
            ])
            ->setDefined([
                'concurrencyCookie',
                'ipAddress',
            ])
            ->resolve($options);

        /** @var \Abacus\AdvanceBundle\Core\Response\LoginResponse $response */
        $response =  $this->apiPost('Login/AutoLogin', $params, LoginResponse::class);
        return $response;
    }

    /**
     * @param array $options
     * @return LoginResponse
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function passwordLessLogin($options)
    {
        $params = (new OptionsResolver())
            ->setRequired([
                'partyId'
            ])
            ->resolve($options);

        /** @var \Abacus\AdvanceBundle\Core\Response\LoginResponse $response */
        $response =  $this->apiPost('Login/PasswordlessLogin', $params, LoginResponse::class);
        return $response;
    }

    /**
     * Get user details.
     *
     * @param $options
     *
     * @return \Abacus\AdvanceBundle\Core\Response\UserDetailsResponse
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function getUserDetails($options)
    {
        $params = (new OptionsResolver())
            ->setRequired([
                'userToken', // User token as received from Login API
            ])
            ->resolve($options);

        /** @var UserDetailsResponse $response */
        $response = $this->apiPost('User/UserDetails', $params, UserDetailsResponse::class);;
        return $response;
    }

    /**
     * Get all user subscriptions for brand.
     *
     * @param $options
     *
     * @return \Abacus\AdvanceBundle\Core\Response\UserSubscriptionsResponse
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function getUserSubscriptions($options)
    {
        $params = (new OptionsResolver())
            ->setRequired([
                'userToken', // User token as received from Login API
            ])
            ->setDefined([
                'ipAddress', // Optional
            ])
            ->resolve($options);

        /** @var UserSubscriptionsResponse $response */
        $response = $this->apiPost('User/GetUserSubscriptions', $params, UserSubscriptionsResponse::class);
        return $response;
    }

    /**
     * Get all products available to user.
     *
     * @param $options
     *
     * @return \Abacus\AdvanceBundle\Core\Response\AvailableProductsResponse
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function getUserProducts($options)
    {
        $params = (new OptionsResolver())
            ->setRequired([
                'brandId'
            ])
            ->setDefined([
                'userToken', // User token as received from Login API - required unless PartyId is provided
                'partyId',
                'ipAddress', // Optional
            ])
            ->resolve($options);

        /** @var AvailableProductsResponse $response */
        $response = $this->apiPost('User/GetListOfAvailableProducts', $params, AvailableProductsResponse::class);
        return $response;
    }

    /**
     * Check if user belongs to a BI query.
     *
     * @param $options
     *
     * @return \Abacus\AdvanceBundle\Core\Response\BelongToBIResponse
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function doesBelongToBI($options)
    {
        $params = (new OptionsResolver())
            ->setRequired([
                'userToken', // User token as received from Login API
            ])
            ->setRequired([
                'FormId', // BI Form id for which to do the checking
            ])
            ->setDefined([
                'ipAddress', // Optional
            ])
            ->resolve($options);

        /** @var BelongToBIResponse $response */
        $response = $this->apiPost('User/DoesPartyBelongToBI', $params, BelongToBIResponse::class);
        return $response;
    }

    /**
     * Log an activity performed by a user combined with MetaItem and MetaCategory classification.
     *
     * @param array $options
     *
     * @return \Abacus\AdvanceBundle\Core\Response\AdvanceResponse|WebActivityResponse
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    public function logWebActivity($options)
    {
        $optionsResolver = new OptionsResolver();
        if (isset($options['partyId'])) {
            // Int representing Advance Party Entity ID
            $optionsResolver
                ->setRequired(['partyId'])
                ->setAllowedTypes('partyId', ['integer']);
        } else {
            // User token as received from Login API
            $optionsResolver->setRequired(['userToken']);
        }

        $params = $optionsResolver->setRequired([
                'actionName',
            ])
            // Optional
            ->setDefined([
                'metaId', // Meta ID of the resource being actioned
                'metaType', // Meta type of the resource
                'metaTitle', // Title (name) of the meta item being actioned
                // How many categories will be submitted with this request
                'numberOfMetaCategories',
                // For WebVision client this is 2
                'metaDataSourceId',
                // Date and time when the action occurred in one of the following formats
                //(yyyy-MM-dd HH:mm:ss or dd/MM/yyyy HH:mm:ss or dd.MM.yyyy HH:mm:ss ; time can be skipped so dd/MM/yyyy has time 00:00:00)
                'actionDate',
                // Type web activity log
                'itemType',
                // Get results if there is this ip address assigned
                'ipAddress',
            ])
            // these enums are in the manual for api rev. 0.9.14, but it seems that Abacus adds frequently new allowed values
            //->setAllowedValues('metaType', ['Story', 'Category', 'Attachment'])
            //->setAllowedValues('actionName', ['Download', 'Login'])
            ->setAllowedTypes('numberOfMetaCategories', ['integer'])
            //->setAllowedValues('itemType', ['Newsletter', 'Website', 'Recruitment', 'Events', 'Directory', 'AccessControl', 'Login', 'ADFrontend'])
            ->resolve($options);

        /** @var WebActivityResponse $response */
        $response = $this->apiPost('User/LogWebActivity', $params, WebActivityResponse::class);
        return $response;
    }
}
