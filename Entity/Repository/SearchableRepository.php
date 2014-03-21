<?php
namespace Padam87\SearchBundle\Entity\Repository;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\EntityRepository;
use Padam87\SearchBundle\Filter\FilterInterface;
use Padam87\SearchBundle\Filter\FilterManager;

/**
 * A repository with the searchService injected to it.
 * Requires the JSMDiExtraBundle or the setSearchService method to be called manually in order to work.
 */
class SearchableRepository extends EntityRepository
{
    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @DI\InjectParams({
     *     "service" = @DI\Inject("padam87_search.filter.manager")
     * })
     *
     * @param FilterManager $service
     */
    public function setFilterManager(FilterManager $service)
    {
        $this->filterManager = $service;
    }

    /**
     * @param FilterInterface $filter
     * @param string          $order
     * @param string          $direction
     *
     * @throws \InvalidArgumentException
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSearchQueryBuilder(FilterInterface $filter, $order = null, $direction = 'ASC')
    {
        if (!($this->filterManager instanceof FilterManager)) {
            throw new \InvalidArgumentException(sprintf(
                '$this->filterManager should be an instance of %s, %s given. '.
                    '(Perhaps you forgot to add the JSMDiExtraBundle?)',
                'Padam87\SearchBundle\Filter\FilterManager',
                is_object($this->filterManager) ? get_class($this->filterManager) : $this->filterManager
            ));
        }

        $filter->setEntityName($this->getEntityName());
        $qb = $this->filterManager->createQueryBuilder($filter);

        if ($order != null) {
            $qb->orderBy($order, $direction);
        }

        return $qb;
    }
}
