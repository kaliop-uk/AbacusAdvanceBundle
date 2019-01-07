<?php

namespace Abacus\AdvanceBundle\Controller\AdvanceStub;

use Symfony\Component\HttpFoundation\Request;

class GateKeeperController extends BaseController
{
    protected $dataProviderServiceId = 'abacus.advance.stub_data_provider.gatekeeper';

    function accessAllowedAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->accessAllowed(
            $request->request->get('userToken'),
            $request->request->get('CookieID'),
            $request->request->get('Url'),
            $request->request->get('MetaId'),
            $request->request->get('MetaType'),
            $request->request->get('ModifiedDate'),
            $request->request->get('StoryPublishDate'),
            $request->request->get('BrandId'),
            $request->request->get('SourceId'),
            $request->request->get('ipAddress')
        );
        return $this->encodeResponse($data);
    }
}
