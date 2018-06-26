<?php

namespace Abacus\AdvanceBundle\Core\Response\Xml;

use Symfony\Component\HttpFoundation\Response;
use Abacus\AdvanceBundle\Api\Entity\StoryInterface;
use Abacus\AdvanceBundle\Core\Response\SimpleXmlResponse;

class StoryCategoryMapping
{
    /**
     * @var \SimpleXMLElement
     */
    private $xml;

    /**
     * CategoryMappingXml constructor.
     *
     * @param StoryInterface $story
     */
    public function __construct(StoryInterface $story)
    {
        $this->xml = new \SimpleXMLElement('<categorymapping />');
        $this->xml->addAttribute('datefrom', date('c'));

        $storyCodeNode = $this->xml->addChild('storycode');
        $storyCodeNode->addAttribute('id', $story->getId());
        $storyCodeNode->addAttribute('headline', $story->getHeadline());

        foreach ($story->getCategories() as $category) {
            $storyCodeNode->addChild('catcode', $category);
        }
    }

    /**
     * @return Response
     */
    public function toResponse()
    {
        return new SimpleXmlResponse($this->xml);
    }
}
