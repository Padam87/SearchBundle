<?php
namespace Padam87\SearchBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use Padam87\SearchBundle\Service\SearchService;

class SearchableRepository extends EntityRepository
{
    protected $searchService;

    /**
     * @DI\InjectParams({
     *     "service" = @DI\Inject("search")
     * })
     */
    public function setSearchService(SearchService $service)
    {
        $this->searchService = $service;
    }

    public function createSearchQueryBuilder($filter, $alias, $order = null, $direction = 'ASC')
    {
        $qb = $this->searchService->createFilter($filter, $alias)->createQueryBuilder($this->getEntityName());

        if ($order != null) {
            $qb->orderBy($order, $direction);
        }

        return $qb;
    }
}
