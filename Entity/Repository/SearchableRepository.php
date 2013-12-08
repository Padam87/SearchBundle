<?php
namespace Padam87\SearchBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use Padam87\SearchBundle\Service\SearchService;

/**
 * A repository with the searchService injected to it.
 * Requires the JSMDiExtraBundle or the setSearchService method to be called manually in order to work.
 */
class SearchableRepository extends EntityRepository
{
    protected $searchService;

    /**
     * @DI\InjectParams({
     *     "service" = @DI\Inject("search")
     * })
     *
     * @param SearchService $service
     */
    public function setSearchService(SearchService $service)
    {
        $this->searchService = $service;
    }

    /**
     * @param mixed  $filter
     * @param string $alias
     * @param string $order
     * @param string $direction
     *
     * @throws \InvalidArgumentException
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSearchQueryBuilder($filter, $alias, $order = null, $direction = 'ASC')
    {
        if (!($this->searchService instanceof SearchService)) {
            throw new \InvalidArgumentException(sprintf(
                '$this->searchService should be an instance of %s, %s given. '.
                    '(Perhaps you forgot to add the JSMDiExtraBundle?)',
                'Padam87\SearchBundle\Service\SearchService',
                is_object($this->searchService) ? get_class($this->searchService) : $this->searchService
            ));
        }

        $qb = $this->searchService->createFilter($filter, $alias)->createQueryBuilder($this->getEntityName());

        if ($order != null) {
            $qb->orderBy($order, $direction);
        }

        return $qb;
    }
}
