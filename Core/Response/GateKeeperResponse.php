<?php

namespace Abacus\AdvanceBundle\Core\Response;

use Abacus\AdvanceBundle\Core\Service\GateKeeper;

class GateKeeperResponse extends AdvanceResponse
{
    /**
     * @inheritdoc
     */
    protected function validateResponseFormat()
    {
        $data = $this->getData();
        return
            /// @todo validate if Status has a known value
            isset($data['GKResult']) &&
            is_array($data['GKResult']) &&
            isset($data['GKResult']['Status']) &&
            isset($data['GKResult']['DataGatheringUrl'])
        ;
    }

    /**
     * Get access status
     * On invalid response or invalid data, an access denied was send
     *
     * @see AccessStatus
     * @return int
     *
     * @todo move isValidResponse() away
     */
    public function getAccessStatus()
    {
        $status = GateKeeper::ACCESS_DENIED;

        if ($this->isValidResponse()) {

            switch ($this->getData()['GKResult']['Status']) {
                case 'AccessGranted':
                    $status = GateKeeper::ACCESS_GRANTED;
                    break;
                //case 'AccessDenied':
                //default:
                //    $status = GateKeeper::ACCESS_DENIED;
            }

        }

        return $status;
    }

    /**
     * Return true if access status is access granted
     *
     * @return bool
     */
    public function hasAccessGranted()
    {
        return $this->getAccessStatus() === GateKeeper::ACCESS_GRANTED;
    }

    /**
     * If empty or undefined, return null else return data gathering url
     *
     * @return string|null
     *
     * @todo move isValidResponse() away
     */
    public function getDataGatheringUrl()
    {
        if ($this->isValidResponse() &&
            $this->getData()['GKResult']['DataGatheringUrl'] !== '') {
            return $this->getData()['GKResult']['DataGatheringUrl'];
        }

        return null;
    }
}
