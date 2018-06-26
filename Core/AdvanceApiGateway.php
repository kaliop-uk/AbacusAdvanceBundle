<?php

namespace Abacus\AdvanceBundle\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Abacus\AdvanceBundle\Core\Service;

class AdvanceApiGateway
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return Service\GateKeeper
     */
    public function getGateKeeperService()
    {
        return $this->container->get('abacus.advance.service.gate_keeper');
    }

    /**
     * @return Service\User
     */
    public function getUserService()
    {
        return $this->container->get('abacus.advance.service.user');
    }
}
