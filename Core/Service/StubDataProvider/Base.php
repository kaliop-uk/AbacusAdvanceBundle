<?php

namespace Abacus\AdvanceBundle\Core\Service\StubDataProvider;

use Symfony\Component\HttpFoundation\Request;
use Abacus\AdvanceBundle\Core\Response\GenericResponse;

abstract class Base
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

    /**
     * @param string $username
     * @param string $password
     * @return array|null
     */
    protected function getUserProfile($username, $password)
    {
        foreach($this->config['users'] as $userData) {
            if (preg_match($userData['LoginRegexp'], $username) && preg_match($userData['PasswordRegexp'], $password)) {
                return $userData;
            }
        }

        return null;
    }

    /**
     * @param string $token
     * @return array|null
     */
    protected function getUserProfileFromToken($token)
    {
        $md5 = explode('_', $token, 2);
        $md5 = $md5[0];

        foreach($this->config['users'] as $userData) {
            if (md5($userData['LoginRegexp']) === $md5) {
                return $userData;
            }
        }

        return null;
    }

    /**
     * @param string $partyId
     * @return array|null
     */
    protected function getUserProfileByPartyId($partyId)
    {
        foreach($this->config['users'] as $userData) {
            if ($userData['PartyId'] === $partyId) {
                return $userData;
            }
        }

        return null;
    }

    /**
     * Creates a unique "session" token that can be used to tface back to a user, without a db/storage :-)
     * @param array $profile
     * @return string
     */
    protected function generateTokenFromUserProfile(array $profile)
    {
        return md5($profile['LoginRegexp']) . '_' . microtime(true);
    }

    /**
     * Generates a generic response for error conditions (which has an empty `data` member)
     * @param int $errCode
     * @param string $message
     * @return GenericResponse
     */
    protected function failedResponse($errCode = 1, $message = 'Invalid token.')
    {
        return new GenericResponse($this->buildResponseArray(
            [],
            'ERROR',
            [
                [
                    'type' => 'error',
                    'code' => $errCode,
                    'msg' => $message
                ]

            ]
        ));
    }
}
