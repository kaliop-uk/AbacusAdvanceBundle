<?php

namespace Abacus\AdvanceBundle\Core\Service\StubDataProvider;

use Abacus\AdvanceBundle\Core\Response\LoginResponse;

class Login extends Base
{
    public function Login(
        $username = null,
        $password = null,
        $concurrencyCookie = null,
        $ipAddress = null
    )
    {
        $profile = $this->getUserProfile($username, $password);
        return $this->createResponse($profile);
    }

    /// @todo implement support for $frontendCookie
    public function autoLogin(
        $frontendCookie = null,
        $concurrencyCookie = null,
        $ipAddress = null
    )
    {
        return new LoginResponse($this->buildResponseArray(
            [
                'LoginDetails' => [
                    'FrontendCookieName' => 'FrontEndCookie',
                    'FrontendCookieValue' => 'test',
                    'ConcurrencyCookieName' => 'ConcurrencyCookie',
                    'ConcurrencyCookieValue' => 'test',
                    'Activated' => 'true',
                    'UserToken' => 'abcdefghij',
                    'Username' => 'lum-all3-live-sub@test.com',
                    'PartyID' => 'lum-all3-live-sub@test.com',
                    'UserRegistrationID' => 123,
                ],
            ]
        ));
    }

    public function passwordlessLogin(
        $partyId = null
    )
    {
        $profile = $this->getUserProfileByPartyId($partyId);
        return $this->createResponse($profile);
    }

    /**
     * @param null|array $profile
     * @return LoginResponse
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     */
    protected function createResponse($profile)
    {
        if (!$profile) {
            return $this->failedLoginResponse();
        }

        return new LoginResponse($this->buildResponseArray(
            [
                'LoginDetails' => [
                    'FrontendCookieName' => $this->config['settings']['FrontEndCookieName'],
                    'FrontendCookieValue' => 'test', // @todo
                    'ConcurrencyCookieName' => $this->config['settings']['ConcurrencyCookieName'],
                    'ConcurrencyCookieValue' => 'test', // @todo
                    'Activated' => 'True',
                    'UserToken' => $this->generateTokenFromUserProfile($profile),
                    'Username' => $profile['Username'],
                    'PartyID' => $profile['partyId'],
                    'UserRegistrationID' => $profile['UserRegistrationID'],
                ],
            ]
        ));
    }

    protected function failedLoginResponse($errCode = 1, $message = 'Invalid username/password.')
    {
        return new LoginResponse($this->buildResponseArray(
            [
                'LoginDetails' => [
                    'FrontendCookieName' => $this->config['settings']['FrontEndCookieName'],
                    'FrontendCookieValue' => 'test', // @todo
                    'ConcurrencyCookieName' => $this->config['settings']['ConcurrencyCookieName'],
                    'ConcurrencyCookieValue' => 'test', // @todo
                    'Activated' => 'False',
                    'UserToken' => '',
                    'Username' => '',
                    'PartyID' => 0,
                    'UserRegistrationID' => 0,
                ],
            ],
            'ERROR',
            [
                [
                    'type' => 'error',
                    'code' => $errCode,
                    'msg' => $message
                ]

            ]
        ));
    }
}
