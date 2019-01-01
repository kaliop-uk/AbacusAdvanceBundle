<?php

namespace Abacus\AdvanceBundle\Core\Service\StubDataProvider;

use Symfony\Component\HttpFoundation\Request;

class Base
{
    const ACCESS_HASH_HEADER = 'ADAPI_ACCESS_HASH';
    const ACCESS_TOKEN_HEADER = 'ADAPI_ACCESS_TOKEN';

    protected $adapiAccessHash;
    protected $adapiAccessToken;
    protected $version;

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function processRequestHeaders(Request $request, $site, $version)
    {
        $this->adapiAccessHash = $request->headers->get(self::ACCESS_HASH_HEADER, null);
        $this->adapiAccessToken = $request->headers->get(self::ACCESS_TOKEN_HEADER, null);
        $this->version = $version;

        return $this; // allow fluent interfaces
    }

    // just a helper for subclasses, to avoid code repetition
    protected function buildResponseArray(array $data, $code = 'OK', array $messages = [])
    {
        return [
            'response' => [
                'version' => $this->version,
                'status' => [
                    'code' => $code,
                    'messages' => $messages
                ],
                'data' => $data
            ]
        ];
    }
}
