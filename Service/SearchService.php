<?php

namespace Padam87\SearchBundle\Service;

use Doctrine\ORM\EntityManager;
use Padam87\SearchBundle\Filter\FilterFactory;

class SearchService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Padam87\SearchBundle\Filter\FilterFactory
     */
    private $factory;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->factory = new FilterFactory($em);
    }

    /**
     * Gets the FilterFactory.
     *
     * @return \Padam87\SearchBundle\Filter\FilterFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Creates a filter
     *
     * @return \Padam87\SearchBundle\Filter\FilterInterface
     */
    public function createFilter($filter, $alias)
    {
        return $this->factory->create($filter, $alias);
    }
}
