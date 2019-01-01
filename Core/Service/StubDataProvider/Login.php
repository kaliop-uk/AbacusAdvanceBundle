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
        /// @todo
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

    public function autoLogin(
        $frontendCookie = null,
        $concurrencyCookie = null,
        $ipAddress = null
    ) {
        /// @todo
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
    ) {
        /// @todo
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
}
