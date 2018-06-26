<?php

namespace Abacus\AdvanceBundle\Api\Service;

use Abacus\AdvanceBundle\Api\Entity\StoryInterface;
use Symfony\Component\HttpFoundation\Response;

interface StoryCategoryMapperInterface
{
    /**
     * @param int $id
     * @return StoryInterface|null
     */
    public function findStory($id);

    /**
     * Allows to manipulate the http response of the controller that returns the XML for the Story/Category mapping
     * @param Response $response
     * @param int $storyId
     */
    public function preResponse(Response $response, $storyId);
}
