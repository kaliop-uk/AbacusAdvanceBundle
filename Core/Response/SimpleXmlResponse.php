<?php

namespace Abacus\AdvanceBundle\Core\Response;

use Symfony\Component\HttpFoundation\Response;

class SimpleXmlResponse extends Response
{
    /**
     * XmlResponse constructor.
     *
     * @param \SimpleXMLElement
     * @param int $status
     * @param array $headers
     */
    public function __construct(\SimpleXMLElement $xml, $status = 200, array $headers = [])
    {
        parent::__construct($xml->asXML(), $status, $headers);
        $this->headers->set('Content-Type', 'application/xml; charset=utf-8');
    }
}
