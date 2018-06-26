<?php

namespace Abacus\AdvanceBundle\Core\Response;

use Abacus\AdvanceBundle\Core\Exception\AdvanceResponseException;

abstract class AdvanceResponse
{
    /** @var array */
    protected $rawResponse = [];

    /** @var array */
    protected $data = [];

    /** @var int|null */
    protected $version;

    /** @var string|null */
    protected $statusCode;

    /** @var array */
    protected $statusMessages = [];

    /** @var bool */
    protected $isValidResponseFormat;

    /**
     * AdvanceResponse constructor.
     * @param array $rawResponse
     * @throws AdvanceResponseException
     */
    public function __construct($rawResponse)
    {
        $this->setRawResponse($rawResponse);
    }

    /**
     * @return array|null
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @param $rawResponse
     * @throws AdvanceResponseException
     */
    protected function setRawResponse($rawResponse)
    {
        $this->rawResponse = $rawResponse;
        if (!is_array($rawResponse)) {
            throw new AdvanceResponseException('Raw response is not an array');
        }
        if (!array_key_exists('response', $rawResponse)) {
            throw new AdvanceResponseException('Response is missing');
        }
        if (!array_key_exists('version', $rawResponse['response'])) {
            throw new AdvanceResponseException('Version is missing');
        }
        if (!array_key_exists('status', $rawResponse['response'])) {
            throw new AdvanceResponseException('Status is missing');
        }
        if (!array_key_exists('code', $rawResponse['response']['status'])) {
            throw new AdvanceResponseException('Status code is missing');
        }
        if (!array_key_exists('data', $rawResponse['response'])) {
            throw new AdvanceResponseException('Data is missing');
        }

        if (array_key_exists('messages', $rawResponse['response']['status'])) {
            $statusMsgs = $rawResponse['response']['status']['messages'];
            if (!is_array($statusMsgs) || $statusMsgs === null || $statusMsgs === '') {
                $statusMsgs = [ $statusMsgs ];
            }
            $this->statusMessages = $statusMsgs;
        }
        $this->statusCode = trim((string)$rawResponse['response']['status']['code']);
        $this->version = $rawResponse['response']['version'];
        $this->data = $rawResponse['response']['data'];

        $this->isValidResponseFormat = $this->validateResponseFormat();
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return null|string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getStatusMessages()
    {
        return $this->statusMessages;
    }

    public function getStatusMessageCodes()
    {
        $codes = array();
        foreach ($this->getStatusMessages() as $statusMessage) {
            if (
                is_array($statusMessage) &&
                isset($statusMessage['code'])
            ) {
                $codes[] = $statusMessage['code'];
            }
        }
        return $codes;
    }

    /**
     * @return bool
     */
    public function isValidResponse()
    {
        return ($this->getStatusCode() === 'OK' && $this->isValidResponseFormat);
        // @todo log warning if $this->validateResponseFormat() fails
    }

    public function isErrorInvalidToken()
    {
        foreach ($this->getStatusMessages() as $statusMessage) {
            if (
                is_array($statusMessage) &&
                isset($statusMessage['type']) &&
                $statusMessage['type'] === 'error' &&
                isset($statusMessage['msg']) &&
                // note: we probably can not check for code = 1 as it is used for different errors by different calls
                strrpos($statusMessage['msg'], 'Invalid user token') !== false
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * To be implemented in each subclass: validate the data payload (this class takes already care of json problems and the 'OK' status)
     * @todo why not throw an exception instead if invalid response format is detected ? This way we can avoid having isValidResponse() calls everywhere in subclasses...
     *
     * @return bool
     */
    abstract protected function validateResponseFormat();

}
