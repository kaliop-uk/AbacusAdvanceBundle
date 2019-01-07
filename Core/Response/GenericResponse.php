<?php

namespace Abacus\AdvanceBundle\Core\Response;

/**
 * A class that does no validation of data nor exposes it in some semantic/decoded way.
 */
class GenericResponse extends AdvanceResponse
{
    protected function validateResponseFormat()
    {
    }
}
