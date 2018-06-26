<?php

namespace Abacus\AdvanceBundle\Api\Service;

use Abacus\AdvanceBundle\Api\Entity\CategoryInterface;
use Symfony\Component\HttpFoundation\Response;

interface CategoryListerInterface
{
    /**
     * @return CategoryInterface[]
     */
    public function getAllCategories();

    /**
     * Allows to manipulate the http response of the controller that returns the XML for the /Category listing
     * @param Response $response
     */
    public function preResponse(Response $response);
}
