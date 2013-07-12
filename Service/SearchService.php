<?php

namespace Padam87\SearchBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityManager;
use Padam87\SearchBundle\Filter\FilterFactory;

/**
 * @DI\Service("search")
 */
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
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
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
