<?php

namespace Abacus\AdvanceBundle\Core\Response;

class LoginResponse extends AdvanceResponse
{
    protected $loginGranted = null;
    protected $activated = null;

    /**
     * @inheritdoc
     */
    protected function validateResponseFormat()
    {
        $data = $this->getData();
        return
            isset($data['LoginDetails']) &&
            is_array($data['LoginDetails']) &&
            isset($data['LoginDetails']['UserRegistrationID']) &&
            isset($data['LoginDetails']['FrontendCookieName']) &&
            isset($data['LoginDetails']['FrontendCookieValue']) &&
            isset($data['LoginDetails']['Activated']) &&
            isset($data['LoginDetails']['UserToken'])
        ;
    }

    /**
     * @return array
     */
    public function getRawLoginDetails()
    {
        return $this->getData()['LoginDetails'];
    }

    /**
     * @return bool
     * @todo move isValidResponse() away ?
     */
    public function isLoginGranted()
    {
        if ($this->loginGranted === null) {
            $this->loginGranted = (
                $this->isValidResponse() &&
                $this->getRawLoginDetails()['UserRegistrationID'] !== null &&
                $this->getRawLoginDetails()['UserRegistrationID'] !== ''
            );
        }
        return $this->loginGranted;
    }

    public function isErrorTooManyCookies()
    {
        foreach ($this->getStatusMessages() as $statusMessage) {
            if (
                is_array($statusMessage) &&
                isset($statusMessage['type']) &&
                $statusMessage['type'] === 'error' &&
                //isset($statusMessage['code']) &&
                //$statusMessage['code'] = 13245
                $statusMessage['msg'] === 'Maximum number of cookies exceeded'
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isActivated()
    {
        if ($this->activated === null ) {
            $this->activated = ('true' === strtolower(trim(''.$this->getRawLoginDetails()['Activated'])));
        }
        return $this->activated;
    }

    public function getUserRegistrationId()
    {
        return $this->getRawLoginDetails()['UserRegistrationID'];
    }

    public function getUsername()
    {
        return $this->getRawLoginDetails()['Username'];
    }

    public function getFrontendCookieName()
    {
        return $this->getRawLoginDetails()['FrontendCookieName'];
    }

    public function getFrontendCookieValue()
    {
        return $this->getRawLoginDetails()['FrontendCookieValue'];
    }

    public function getConcurrencyCookieName()
    {
        return $this->getRawLoginDetails()['ConcurrencyCookieName'];
    }

    public function getConcurrencyCookieValue()
    {
        return $this->getRawLoginDetails()['ConcurrencyCookieValue'];
    }

    public function getUserToken()
    {
        return $this->getRawLoginDetails()['UserToken'];
    }

    public function getPartyID()
    {
        return $this->getRawLoginDetails()['PartyID'];
    }

    public function getIpSetLxRegisteredID()
    {
        return $this->getRawLoginDetails()['IpSetLxRegisteredID'];
    }

    public function getIpAddressSetId()
    {
        return $this->getRawLoginDetails()['IpAddressSetId'];
    }

    public function getIpSetRid()
    {
        return $this->getRawLoginDetails()['IpSetRid'];
    }

    public function getIpSetName()
    {
        return $this->getRawLoginDetails()['IpSetName'];
    }
}
