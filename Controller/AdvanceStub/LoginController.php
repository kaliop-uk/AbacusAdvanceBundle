<?php

namespace Abacus\AdvanceBundle\Controller\AdvanceStub;

use Symfony\Component\HttpFoundation\Request;

class LoginController extends BaseController
{
    protected $dataProviderServiceId = 'abacus.advance.stub_data_provider.login';

    function loginAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->login(
            $request->request->get('Username'),
            $request->request->get('Password'),
            $request->request->get('concurrencyCookie'),
            $request->request->get('ipAddress')
        );
        return $this->encodeResponse($data);
    }

    function autoLoginAction(Request $request, $site, $version)
    {
        $dataProvider = $this->beginAction($request, $site, $version);
        $data = $dataProvider->autoLogin(
            $request->request->get('frontendCookie'),
            $request->request->get('concurrencyCookie'),
            $request->request->get('ipAddress')
        );
        return $this->encodeResponse($data);
    }

    function passwordlessLogin(Request $request, $site, $version)
    {
        $data = $dataProvider->passwordlessLogin(
            $request->request->get('partyId')
        );
        return $this->encodeResponse($data);
    }
}
