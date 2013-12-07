<?php
namespace Padam87\SearchBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Padam87\SearchBundle\Filter\FilterInterface;
use Padam87\SearchBundle\Service\SearchService;

class SearchableRepository extends EntityRepository
{
    protected $searchService;

    /**
     * @param SearchService $service
     */
    public function setSearchService(SearchService $service)
    {
        $this->searchService = $service;
    }

    /**
     * @param \Padam87\SearchBundle\Filter\FilterInterface $filter
     * @param string                                       $alias
     * @param string                                       $order
     * @param string                                       $direction
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSearchQueryBuilder(FilterInterface $filter, $alias, $order = null, $direction = 'ASC')
    {
        $qb = $this->searchService->createFilter($filter, $alias)->createQueryBuilder($this->getEntityName());

        if ($order != null) {
            $qb->orderBy($order, $direction);
        }

        return $qb;
    }
}
