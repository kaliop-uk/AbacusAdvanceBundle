<?php

namespace Abacus\AdvanceBundle\Core\Response;

use Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException;
use Abacus\AdvanceBundle\Core\Entity\Subscription;

class UserSubscriptionsResponse extends AdvanceResponse
{
    protected $subscriptionModelList;

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
    public function getRawSubscriptionList()
    {
        if ($this->isValidResponse()) {
            return $this->getData()['list'];
        } else {
            throw new AdvanceResponseException('Invalid Response from remote service');
        }
    }

    /**
     * @return Subscription[]
     * @throws AdvanceResponseException
     */
    public function getSubscriptionList()
    {
        if ($this->subscriptionModelList === null) {
            $this->subscriptionModelList = [];
            $rawSubscriptions = $this->getRawSubscriptionList();
            foreach ($rawSubscriptions as $rawSub) {
                $this->subscriptionModelList[] = (new Subscription($rawSub));
            }
        }
        return $this->subscriptionModelList;
    }
}
