<?php

namespace Abacus\AdvanceBundle\Core;

interface AdvanceClientAwareInterface
{
    /**
     * @return AdvanceClient
     */
    public function getAdvanceClient();

    /**
     * @param AdvanceClient $client
     * @return $this
     */
    public function setAdvanceClient(AdvanceClient $client);
}
