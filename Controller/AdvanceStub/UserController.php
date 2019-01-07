<?php

namespace Abacus\AdvanceBundle\Controller\AdvanceStub;

use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    protected $dataProviderServiceId = 'abacus.advance.stub_data_provider.user';

    function detailsAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->details(
            $request->request->get('userToken')
        );
        return $this->encodeResponse($data);
    }

    function getUserSubscriptionsAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->getUserSubscriptions(
            $request->request->get('userToken'),
            $request->request->get('ipAddress')
        );
        return $this->encodeResponse($data);
    }

    function getListOfAvailableProductsAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->getUserSubscriptions(
            $request->request->get('userToken'),
            $request->request->get('partyId'),
            $request->request->get('brandId'),
            $request->request->get('ipAddress')
        );
        return $this->encodeResponse($data);
    }

    function doesPartyBelongToBIAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->doesPartyBelongToBI(
            $request->request->get('userToken'),
            $request->request->get('formId')
        );
        return $this->encodeResponse($data);
    }

    function logWebActivityAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->logWebActivity(
            $request->request->get('userToken'),
            $request->request->get('partyId'),
            $request->request->get('metaId'),
            $request->request->get('metaType'),
            $request->request->get('metaTitle'),
            $request->request->get('actionName'),
            $request->request->get('numberOfMetaCategories'),
            $request->request->get('metaDataSourceId'),
            $request->request->get('actionDate'),
            $request->request->get('itemType'),
            $request->request->get('ipAddress')
        );
        return $this->encodeResponse($data);
    }
}
