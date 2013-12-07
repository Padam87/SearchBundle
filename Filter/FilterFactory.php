<?php

namespace Padam87\SearchBundle\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;

class FilterFactory
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em = null)
    {
        $this->setEntityManager($em);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(EntityManager $em = null)
    {
        $this->em = $em;
    }

    /**
     * Creates a filter
     *
     * @param string $filter
     * @param string $alias
     * @return \Padam87\SearchBundle\Filter\FilterInterface
     * @throws \Exception
     */
    public function create($filter, $alias)
    {
        if ($this->isCollection($filter)) {
            $filter = new CollectionFilter($filter, $alias);
        } elseif ($this->isEntity($filter)) {
            $filter = new EntityFilter($filter, $alias);
        } elseif ($this->isArray($filter)) {
            $filter = new ArrayFilter($filter, $alias);
        } else {
            throw new \Exception('Invalid Filter for ' . $alias); // TODO
        }

        $filter->setEntityManager($this->em);

        return $filter;
    }

    /**
     * @param mixed $filter
     * @return boolean
     */
    protected function isArray($filter)
    {
        return is_array($filter); // TODO: no matrix allowed
    }

    /**
     * @param mixed $filter
     * @return boolean
     */
    protected function isEntity($filter)
    {
        return is_object($filter); // TODO: do this properly, with annotation reader, until then, this will do
    }

    /**
     * @param mixed $filter
     * @return boolean
     */
    protected function isCollection($filter)
    {
        return $filter instanceof Collection;
    }
}
