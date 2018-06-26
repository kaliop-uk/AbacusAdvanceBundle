<?php

namespace Abacus\AdvanceBundle\Core\Response;

use Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException;

class UserDetailsResponse extends AdvanceResponse
{
    /**
     * @inheritdoc
     */
    protected function validateResponseFormat()
    {
        $data = $this->getData();
        return
            isset($data['Person']) &&
            is_array($data['Person']) &&
            isset($data['Person']['Forname']) &&
            isset($data['Person']['Surname'])
        ;
    }

    /**
     * @return mixed
     * @throws AdvanceResponseException
     * @todo move the exception throwing to validateResponseFormat...
     */
    public function getRawPersonDetails()
    {
        if ($this->isValidResponse()) {
            return $this->getData()['Person'];
        } else {
            throw new AdvanceResponseException('Invalid Response from remote service');
        }
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getRawPersonDetails()['Forname'];
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getRawPersonDetails()['Surname'];
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->getRawPersonDetails()['Gender'];
    }

    /**
     * @return string
     */
    public function getBirthdayDate()
    {
        return $this->getRawPersonDetails()['DateOfBirthday'];
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->getRawPersonDetails()['Salutation'];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->getRawPersonDetails()['UserToken'];
    }

    /**
     * @return string
     */
    public function getUserRegistrationId()
    {
        return $this->getRawPersonDetails()['UserRegistrationID'];
    }
}
