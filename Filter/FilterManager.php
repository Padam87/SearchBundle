<?php

namespace Padam87\SearchBundle\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Padam87\SearchBundle\Filter\Converter\ConverterManager;

class FilterManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Converter\ConverterManager
     */
    protected $converterManager;

    /**
     * @param EntityManager    $em
     * @param ConverterManager $converterManager
     */
    public function __construct(EntityManager $em, ConverterManager $converterManager)
    {
        $this->em               = $em;
        $this->converterManager = $converterManager;
    }

    /**
     * Creates a query builder based on the filter
     *
     * @param FilterInterface $filter
     * @param array           $collectionHandling
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder(FilterInterface $filter, $collectionHandling = array())
    {
        $converter = $this->converterManager->getConverter($filter);

        $queryBuilder = $this->em
            ->getRepository($filter->getEntityName())
            ->createQueryBuilder($filter->getAlias())
        ;

        $queryBuilder->select("DISTINCT " . $filter->getAlias());

        if ($converter->toExpr($filter) != false) {
            $queryBuilder->where($converter->toExpr($filter));

            foreach ($converter->toParameters($filter) as $parameter) {
                $queryBuilder->setParameter($parameter['token'], $parameter['value']);
            }
        }

        if ($filter->getEntityName() != null) {
            $associations = $this->em->getClassMetadata($filter->getEntityName())->getAssociationNames();

            foreach ($associations as $name) {
                if (!$this->em->getClassMetadata($filter->getEntityName())->isCollectionValuedAssociation($name)) {
                    continue;
                }

                if ($converter->get($filter, $name) == null || $converter->get($filter, $name)->count() == 0) {
                    continue;
                }

                if (isset($collectionHandling[$name]) && $collectionHandling[$name] == 'AND') {
                    foreach ($converter->get($filter, $name) as $k => $data) {
                        $subfilter = new Filter($data, null, $name . $k);
                        $this->joinToQueryBuilder($subfilter, $queryBuilder, $name);
                    }
                } else {
                    $subfilter = new Filter($converter->get($filter, $name), null, $name);
                    $this->joinToQueryBuilder($subfilter, $queryBuilder, $name);
                }
            }
        }

        return $queryBuilder;
    }

    /**
     * Applies the filter to an already existing query builder
     *
     * @param FilterInterface $filter
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param string $joinName
     * @param string $joinType
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function joinToQueryBuilder(FilterInterface $filter, QueryBuilder $queryBuilder, $joinName, $joinType = 'inner')
    {
        $converter   = $this->converterManager->getConverter($filter);
        $expr        = $converter->toExpr($filter);
        $rootAliases = $queryBuilder->getRootAliases();
        $rootAlias   = $rootAliases[0];

        if ($expr != false || $joinType == 'left') {
            switch ($joinType) {
                case 'left':
                    $queryBuilder->leftJoin($rootAlias . '.' . $joinName, $filter->getAlias());

                    break;
                case 'inner':
                default:
                    $queryBuilder->join($rootAlias . '.' . $joinName, $filter->getAlias());
            }

            if ($expr != false) {
                $queryBuilder->andWhere($expr);

                foreach ($converter->toParameters($filter) as $parameter) {
                    $queryBuilder->setParameter($parameter['token'], $parameter['value']);
                }
            }
        }

        return $queryBuilder;
    }
} 