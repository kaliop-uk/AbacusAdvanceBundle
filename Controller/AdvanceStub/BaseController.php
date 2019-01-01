<?php

namespace Abacus\AdvanceBundle\Controller\AdvanceStub;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Abacus\AdvanceBundle\Core\Response\AdvanceResponse;

class BaseController extends Controller
{
    const RESPONSE_FORMAT_HEADER = 'ADAPI_RESPONSE_FORMAT';

    protected $adapiResponseFormat = 'xml'; // xml is the default
    protected $dataProviderServiceId;

    protected function beginAction($request, $site, $version)
    {
        $this->processRequestHeaders($request, $site, $version);

        $dataProvider = $this->get($this->dataProviderServiceId);
        $dataProvider->processRequestHeaders($request, $site, $version);
        return $dataProvider;
    }

    protected function processRequestHeaders(Request $request, $site, $version)
    {
        $this->adapiResponseFormat = $request->headers->get(self::RESPONSE_FORMAT_HEADER, 'json');
    }

    protected function encodeResponse(AdvanceResponse $response, $httpStatus = 200, $headers = array())
    {
        $data = [
            'response' => [
                'version' => $response->getVersion(),
                'status' => [
                    'code' => $response->getStatusCode(),
                    'messages' => $response->getStatusMessages()
                ],
                'data' => $response->getData(),
            ]
        ];

        switch ($this->adapiResponseFormat) {
            case 'json':
                return new JsonResponse($data, $httpStatus, $headers);
            default:
                throw new \Exception("Unsupported response format '{$this->adapiResponseFormat}'");
        }
    }
}
