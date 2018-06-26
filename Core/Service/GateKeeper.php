<?php

namespace Abacus\AdvanceBundle\Core\Service;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Abacus\AdvanceBundle\Core\AdvanceService;
use Abacus\AdvanceBundle\Core\Response\GateKeeperResponse;

class GateKeeper extends AdvanceService
{

    const META_TYPE_STORY = 'Story';
    const META_TYPE_CATEGORY = 'Category';

    const ACCESS_DENIED = 0;
    const ACCESS_GRANTED = 1;

    /**
     * The service is used to determine whether access should be allowed or not. In case it returns AccessDenied there is a
     * possibility that Incremental data capture process would allow access if given form is completed. This is the purpose of
     * DataGatheringUrl property in return model. It is a path and a query string to FE site that would take user through
     * the Incremental data capture flow. To correctly invoke the flow, caller needs to add "retUrl" parameter to that value (so
     * via &retUrl=) which would contain URL escaped link to which the FE should redirect after the survey is completed.
     *
     * https://{API-HOST}/GateKeeper/AccessAllowed/1
     *
     * Ex: https://adgate.abasoft.co.uk/GateKeeper/AccessAllowed/1
     *
     * @param $options
     *
     * @return GateKeeperResponse
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException
     * @throws \Abacus\AdvanceBundle\Core\Exception\AdvanceClientException
     * @throws \Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\OptionDefinitionException
     * @throws \Symfony\Component\OptionsResolver\Exception\NoSuchOptionException
     * @throws \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function accessAllowed($options)
    {
        $params = (new OptionsResolver())
            ->setDefined([
                // for logged in users (optional if cookieid supplied)
                'userToken',
                // for anonymous users (optional if user token supplied)
                'CookieID',
                // this is resource url which is being accessed. It is used to retrieve products that are associated with this url (plus the ones that come from meta-mapping information)
                'Url',
                // this is meta id of the resource; The ID from the frontend engine (WebVision) – which is of the indicated type – see MetaType parameter
                'MetaId',
                // – this is meta type of the resource. Possible values: Story, Category
                'MetaType',
                // last modified date for story or category mapping depending on what is being requested (this is required for API to check whether the data it has in cache is outdated or not)
                'ModifiedDate',
                // published date for the story.This is used to determine expiry date for category rules
                'StoryPublishDate',
                // brandid under which the meta item is stored
                'BrandId',
                // ID number for the source of metadata (webvision, ...)
                'SourceId',
                // Get results if there is this ip address assigned
                'ipAddress',
            ])
            ->resolve($options);

        /** @var GateKeeperResponse $response */
        $response = $this->apiPost('GateKeeper/AccessAllowed', $params, GateKeeperResponse::class);
        return $response;
    }

}
