<?php

namespace Abacus\AdvanceBundle\Core\Response;

class WebActivityResponse extends AdvanceResponse
{
    /**
     * @inheritdoc
     */
    protected function validateResponseFormat()
    {
        return true;
    }
}
