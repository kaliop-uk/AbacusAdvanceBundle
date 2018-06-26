<?php

namespace Abacus\AdvanceBundle\Core;

use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use Abacus\AdvanceBundle\Core\Service\GateKeeper;
use Abacus\AdvanceBundle\Core\Exception\AdvanceClientException;

class AdvanceClient
{
    const MAX_LOG_LENGTH = 10000;

    /** @var GateKeeper */
    protected $gateKeeper;

    /** @var string */
    protected $apiBaseUrl;

    /** @var string */
    protected $apiAccessHash;

    /** @var string */
    protected $apiAccessToken;

    /** @var int|string */
    protected $apiVersion;

    /** @var LoggerInterface */
    protected $logger;

    /** @var ClientInterface */
    protected $httpClient;

    protected $responseFormat = 'json';

    /**
     * @param string $baseUrl
     * @param string $apiAccessHash
     * @param string $apiAccessToken
     * @param int $apiVersion
     * @param array $guzzleOptions
     */
    public function __construct($baseUrl, $apiAccessHash, $apiAccessToken, $apiVersion=1, $guzzleOptions = array())
    {
        $this->apiBaseUrl = $baseUrl;
        $this->apiVersion = $apiVersion;
        $this->apiAccessHash = $apiAccessHash;
        $this->apiAccessToken = $apiAccessToken;

        $this->httpClient = new Client($guzzleOptions);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getApiBaseUrl()
    {
        return $this->apiBaseUrl;
    }

    public function getDefaultHeaders()
    {
        return [
            'ADAPI_ACCESS_HASH'     => $this->apiAccessHash,
            'ADAPI_ACCESS_TOKEN'    => $this->apiAccessToken,
            'ADAPI_RESPONSE_FORMAT' => $this->responseFormat,
        ];
    }

    /**
     * @param string $advServiceUri without hostname and port, ex: GateKeeper/AccessAllowed
     * @param null|int|string $version following ADvance convention, the version is appended to the $advServiceUri
     *
     * @return string
     */
    protected function getEndpointUrl($advServiceUri, $version = null)
    {
        if ($version === null) {
            $version = $this->apiVersion;
        }

        /// @todo remove the excess slashes if $advServiceUri or $version are null
        return sprintf('%s/%s/%s',
           trim(''.$this->getApiBaseUrl(), ' /'),
           trim(''.$advServiceUri, ' /'),
           trim(''.$version, ' /')
        );
    }

    /**
     * @param string $content
     * @return mixed
     */
    protected function decodeResponseBody($content)
    {
        switch($this->responseFormat) {
            case 'json':
                return (new JsonDecode(true))->decode($content, 'json');
            /// @todo support xml decoding
            default:
                throw new UnexpectedValueException("Response format '{$this->responseFormat}' not yet supported");
        }
    }
    /**
     * @param string $apiServiceUri
     * @param array $data
     * @param int|string|null $apiVersion
     *
     * @return array|null
     * @throws AdvanceClientException
     */
    public function apiPost($apiServiceUri, array $data=[], $apiVersion = null)
    {
        $result = null;

        try {
            $url = $this->getEndpointUrl($apiServiceUri, $apiVersion);
            if ($this->logger) $this->logger->debug("Call to ADvance url: '$url' with data: " . $this->formatForLog($data));

            $time = microtime(true);
            $res = $this->httpClient->request('POST', $url, [
                'form_params' => $data,
                'headers'     => $this->getDefaultHeaders(),
            ]);
            $time = microtime(true) - $time;
            if ($this->logger) $this->logger->debug("Response time: " . sprintf("%.3f", $time * 1000) . " ms");

            // This is important: a special character is present at the start of the response and preventing from decoding...
            // gg: maybe it's a BOM?
            $content = trim($res->getBody()->getContents(), '﻿ ');

            $result = $this->decodeResponseBody($content);
        } catch (ConnectException $guzzleConnectException) {
            // timeout and connect_timeout throw a GuzzleHttp\Exception\ConnectException
            $time = microtime(true) - $time;
            if ($this->logger) $this->logger->debug('Response time: '.sprintf('%.3f', $time * 1000).' ms');

            if ($this->logger) $this->logger->error($guzzleConnectException->getMessage(), [get_class($guzzleConnectException)]);
            throw new AdvanceClientException(
                $guzzleConnectException->getMessage(),
                $guzzleConnectException->getCode() > 0 ? $guzzleConnectException->getCode() : 504, // 'Gateway Timeout'
                $guzzleConnectException
            );
        } catch (ClientException $guzzleClientException) {
            $time = microtime(true) - $time;
            if ($this->logger) $this->logger->debug("Response time: " . sprintf("%.3f", $time * 1000) . " ms");

            if ($this->logger) $this->logger->error($guzzleClientException->getMessage(), [get_class($guzzleClientException)]);
            throw new AdvanceClientException(
                $guzzleClientException->getResponse()->getReasonPhrase(),
                $guzzleClientException->getCode() > 0 ? $guzzleClientException->getCode() : 502, // Bad Gateway
                $guzzleClientException
            );
        } catch (ServerException $serverException) {
            $time = microtime(true) - $time;
            if ($this->logger) $this->logger->debug("Response time: " . sprintf("%.3f", $time * 1000) . " ms");

            if ($this->logger) $this->logger->error($serverException->getMessage(), [get_class($serverException)]);
            throw new AdvanceClientException(
                $serverException->getMessage(),
                $serverException->getCode() > 0 ? $serverException->getCode() : 502, // Bad Gateway
                $serverException
            );
        } catch (UnexpectedValueException $unexpectedValueException) {
            if ($this->logger) $this->logger->error($unexpectedValueException->getMessage(), [get_class($unexpectedValueException)]);
            throw new AdvanceClientException(
                $unexpectedValueException->getMessage(),
                $unexpectedValueException->getCode() > 0 ? $unexpectedValueException->getCode() : 502, // Bad Gateway
                $unexpectedValueException
            );
        }

        return $result;
    }

    protected function formatForLog($data)
    {
        if (is_array($data) && isset($data['Password'])) {
            // scrub the pwd from logs
            $data['Password'] = '...';
        }
        return substr(preg_replace('/\n */', ' ', var_export($data, true)), 0, self::MAX_LOG_LENGTH);
    }
}
