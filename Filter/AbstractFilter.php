<?php

namespace Padam87\SearchBundle\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractFilter
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * Alias for the query entity
     *
     * @var string
     */
    protected $alias;

    /**
     * Name of the filtered entity
     *
     * @var string
     */
    protected $entityName = null;

    /**
     * Specifies the search method for collections (AND / OR), defaults to OR if not specified
     *
     * @var array
     */
    protected $collectionHandling = array();

    /**
     * Specifies the search method for a field, defults to "=" if not specified
     *
     * @var array
     */
    protected $defaultOperators = array();

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager(EntityManager $em = null)
    {
        $this->_em = $em;
    }

    /**
     * Sets the default operator to a field
     *
     * @param string $field
     * @param string $operator
     * @return \Padam87\SearchBundle\Filter\AbstractFilter
     */
    public function setDefaultOperator($field, $operator)
    {
        $this->defaultOperators[$field] = $operator;

        return $this;
    }

    /**
     * Finds the operator for a field
     *
     * @param string $field
     * @param string $value
     * @return type
     */
    protected function processDefaultOperator($field, $value)
    {
        $operatorHandler = new OperatorHandler();

        if (isset($this->defaultOperators[$field])) {
            if (isset(OperatorHandler::$nameOperators[$this->defaultOperators[$field]])
                && $operatorHandler->getOperator($field, OperatorHandler::OPERATOR_NAME) === false) {

                $field = $field . $this->defaultOperators[$field];
            } elseif (isset(OperatorHandler::$valueOperators[$this->defaultOperators[$field]])
                && $operatorHandler->getOperator($field, OperatorHandler::OPERATOR_VALUE) === false) {

                $value = $value . $this->defaultOperators[$field];
            }
        }

        return compact('field', 'value');
    }

    /**
     * Creates a query builder based on the filter
     *
     * @param string $entityName
     * @param array $collectionHandling
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder($entityName, $collectionHandling = array())
    {
        $this->entityName = $entityName;
        $this->collectionHandling = $collectionHandling;

        $queryBuilder = $this->_em->getRepository($this->entityName)->createQueryBuilder($this->alias);

        return $this->applyToQueryBuilder($queryBuilder);
    }

    /**
     * Applies the filter to an already existing query builder
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param string $joinName
     * @param string $joinType
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function applyToQueryBuilder(QueryBuilder $queryBuilder, $joinName = null, $joinType = 'inner')
    {
        if ($this->alias == $queryBuilder->getRootAlias()) {
            $queryBuilder->select("DISTINCT " . $this->alias);

            if ($this->toExpr() != false) {
                $queryBuilder->where($this->toExpr());

                foreach ($this->toParameters() as $parameter) {
                    $queryBuilder->setParameter($parameter['token'], $parameter['value']);
                }
            }

            if ($this->entityName != null) {
                $associations = $this->_em->getClassMetadata($this->entityName)->getAssociationNames();

                foreach ($associations as $name) {
                    if ($this->_em->getClassMetadata($this->entityName)->isCollectionValuedAssociation($name)) {
                        $factory = new FilterFactory($this->_em);

                        if ($this->get($name) != null && $this->get($name)->count() > 0) {
                            if (isset($this->collectionHandling[$name]) && $this->collectionHandling[$name] == 'AND') {
                                foreach ($this->get($name) as $k => $filter) {
                                    $queryBuilder = $factory->create($filter, $name . $k)->applyToQueryBuilder($queryBuilder, $name);
                                }
                            } else {
                                $queryBuilder = $factory->create($this->get($name), $name)->applyToQueryBuilder($queryBuilder, $name);
                            }
                        }
                    }
                }
            }
        } elseif ($joinName != null) {
            if ($this->toExpr() != false || $joinType == 'left') {
                switch ($joinType) {
                    case 'left':
                        $queryBuilder->leftJoin($queryBuilder->getRootAlias() . '.' . $joinName, $this->alias);

                        break;
                    case 'inner':
                    default:
                        $queryBuilder->join($queryBuilder->getRootAlias() . '.' . $joinName, $this->alias);
                }

                if ($this->toExpr() != false) {
                    $queryBuilder->andWhere($this->toExpr());

                    foreach ($this->toParameters() as $parameter) {
                        $queryBuilder->setParameter($parameter['token'], $parameter['value']);
                    }
                }
            }
        }

        return $queryBuilder;
    }
}
