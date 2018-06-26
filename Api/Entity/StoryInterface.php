<?php

namespace Abacus\AdvanceBundle\Api\Entity;

/**
 * Stories are returned by the user application to Advance. They are grouped in Categories.
 */
interface StoryInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getHeadline();

    /**
     * @return int[]
     */
    public function getCategories();
}
