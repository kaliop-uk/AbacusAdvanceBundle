<?php

namespace Abacus\AdvanceBundle\Core;

use Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException;
use Abacus\AdvanceBundle\Core\Exception\AdvanceClientException;
use Abacus\AdvanceBundle\Core\Response\AdvanceResponse;

abstract class AdvanceService implements AdvanceClientAwareInterface
{
    use AdvanceClientAwareTrait;

    public function __construct($client)
    {
        $this->setAdvanceClient($client);
    }

    /**
     * @param string $apiServiceUri
     * @param array $data
     * @param string $returnClassName class name of class that extends AdvanceResponse
     * @param int|string|null $apiVersion
     *
     * @return AdvanceResponse or a subclass of AdvanceResponse
     * @throws AdvanceResponseException
     * @throws AdvanceClientException
     */
    protected function apiPost($apiServiceUri, array $data = [], $returnClassName = AdvanceResponse::class, $apiVersion = null)
    {
        $response = $this->getAdvanceClient()->apiPost($apiServiceUri, $data, $apiVersion);

        $advanceResponse = new $returnClassName($response);

        // gg: why are we not doing this check before creating the object?
        if (!($advanceResponse instanceof AdvanceResponse)) {
            throw new AdvanceResponseException("$returnClassName must be an instance of " . AdvanceResponse::class);
        }

        return $advanceResponse;
    }
}
