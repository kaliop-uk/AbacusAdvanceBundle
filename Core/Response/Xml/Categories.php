<?php

namespace Abacus\AdvanceBundle\Core\Response\Xml;

use Symfony\Component\HttpFoundation\Response;
use Abacus\AdvanceBundle\Api\Entity\CategoryInterface;
use Abacus\AdvanceBundle\Core\Response\SimpleXmlResponse;

class Categories
{
    const PORTAL_CODE = 1;

    const DATE_FORMAT = 'c';

    /**
     * @var \SimpleXMLElement
     */
    private $xml;

    /**
     * MarketListXml constructor.
     */
    public function __construct()
    {
        $this->xml = new \SimpleXMLElement('<categories />');
    }

    /**
     * @param CategoryInterface $category
     */
    public function addCategory(CategoryInterface $category)
    {
        $marketNode = $this->xml->addChild('category');

        $marketNode->addAttribute('id', $category->getId());
        $marketNode->addChild('portalcode', self::PORTAL_CODE);
        $marketNode->addChild('category', $category->getName());
        $marketNode->addChild('catlevel', 1);
        $marketNode->addChild('priority', $category->getPriority());
        $marketNode->addChild('parent');
        $marketNode->addChild('parents');

        $publishDate = $category->getCreatedDate();
        $marketNode->addChild('datecreated', $publishDate instanceof \DateTime ?
            $publishDate->format(self::DATE_FORMAT) :
            null
        );
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->xml['url'] = $url;
    }

    /**
     * @return Response
     */
    public function toResponse()
    {
        return new SimpleXmlResponse($this->xml);
    }
}
