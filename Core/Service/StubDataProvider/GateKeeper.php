<?php

namespace Abacus\AdvanceBundle\Core\Service\StubDataProvider;

use Symfony\Component\HttpFoundation\Request;
use Abacus\AdvanceBundle\Core\Response\GateKeeperResponse;
use Abacus\AdvanceBundle\Core\Service\GateKeeper as GateKeeperSvc;

class GateKeeper extends Base
{
    public function accessAllowed(
        $userToken = null,
        $CookieID = null,
        $Url = null,
        $MetaId = null,
        $MetaType = null,
        $ModifiedDate = null,
        $StoryPublishDate = null,
        $BrandId = null,
        $SourceId = null,
        $ipAddress = null
        )
    {
        $profile = $this->getUserProfileFromToken($userToken);

        if (!$profile) {
            return $this->failedResponse();
        }

        /// @todo implement check based on data from profile
        return new GateKeeperResponse($this->buildResponseArray([
            'GKResult' => [
                'Status' => GateKeeperSvc::ACCESS_GRANTED,
                'DataGatheringUrl' => null
            ]
        ]));
    }
}
