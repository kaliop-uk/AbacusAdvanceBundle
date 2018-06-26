<?php

namespace Abacus\AdvanceBundle\Core;

trait AdvanceClientAwareTrait
{
    /**
     * @var AdvanceClient
     */
    protected $advanceClient;

    /**
     * @return AdvanceClient
     */
    public function getAdvanceClient()
    {
        return $this->advanceClient;
    }

    /**
     * @param AdvanceClient $client
     * @return $this
     */
    public function setAdvanceClient(AdvanceClient $client)
    {
        $this->advanceClient = $client;
        return $this;
    }
}
