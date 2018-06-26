<?php

namespace Abacus\AdvanceBundle\Core\Response;

class BelongToBIResponse extends AdvanceResponse
{
    protected $doesBelong = null;

    /**
     * @inheritdoc
     */
    protected function validateResponseFormat()
    {
        $data = $this->getData();
        return
            isset($data['BIResult']) &&
            is_array($data['BIResult']) &&
            isset($data['BIResult']['Status'])
        ;
    }

    /**
     * @return bool
     * @todo move isValidResponse() away
     */
    public function doesBelongToBI()
    {
        if ($this->doesBelong === null) {
            $this->doesBelong = (
                $this->isValidResponse() &&
                $this->getData()['BIResult']['Status'] == 'True'
            );
        }
        return $this->doesBelong;
    }
}
