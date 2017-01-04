<?php

namespace IngoWalther\ImageMinifyApi\Security;

use IngoWalther\ImageMinifyApi\Database\UserRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ApiKeyCheck
 * @package IngoWalther\ImageMinifyApi\Security
 */
class ApiKeyCheck
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ApiKeyCheck constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Checks for valid API-Key
     * @param string $apiKey
     * @return array|bool
     */
    public function check($apiKey)
    {
        return $this->isKeyValid($apiKey);
    }

    /**
     * Checks if API-Key is valid
     * @param string $apiKey
     * @return array|bool
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    private function isKeyValid($apiKey)
    {
        $user = $this->userRepository->findUserByKey($apiKey);

        if(!$user) {
            throw new AccessDeniedHttpException('Your API key is not valid');
        }
        return $user;
    }
}