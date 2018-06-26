<?php

namespace Abacus\AdvanceBundle\Api\Entity;

/**
 * Categories are returned by the user application to Advance. They are groups of Stories
 */
interface CategoryInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return \DateTime
     */
    public function getCreatedDate();
}
