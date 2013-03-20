<?php

namespace Padam87\SearchBundle\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Collection;

class FilterFactory
{
    protected $_em;

    public function __construct(EntityManager $em = null)
    {
        $this->setEntityManager($em);
    }

    public function setEntityManager(EntityManager $em = null)
    {
        $this->_em = $em;
    }

    public function create($filter, $alias)
    {
        if ($this->isCollection($filter)) {
            $filter = new CollectionFilter($this->_em, $filter, $alias);
        } elseif ($this->isEntity($filter)) {
            $filter = new EntityFilter($this->_em, $filter, $alias);
        } elseif ($this->isArray($filter)) {
            $filter = new ArrayFilter($this->_em, $filter, $alias);
        } else {
            throw new \Exception('Invalid Filter for ' . $alias); // TODO
        }

        return $filter;
    }

    protected function isArray($filter)
    {
        return is_array($filter); // TODO: no matrix allowed
    }

    protected function isEntity($filter)
    {
        return is_object($filter); // TODO: do this properly, with annotation reader, until then, this will do
    }

    protected function isCollection($filter)
    {
        return $filter instanceof Collection;
    }
}
