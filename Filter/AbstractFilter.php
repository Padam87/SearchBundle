<?php

namespace Padam87\SearchBundle\Filter;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractFilter
{
    protected $_em;
    protected $alias;
    protected $entityName = null;
    protected $collectionHandling = array();
    protected $defaultOperators = array();

    public function __construct(EntityManager $em = null)
    {
        $this->setEntityManager($em);
    }

    public function setEntityManager(EntityManager $em = null)
    {
        $this->_em = $em;
    }

    public function isMultipleLevel()
    {
        return false;
    }

    public function setDefaultOperator($field, $operator)
    {
        $this->defaultOperators[$field] = $operator;

        return $this;
    }

    protected function processDefaultOperator($field, $value)
    {
        $operatorHandler = new OperatorHandler();

        if (isset($this->defaultOperators[$field])) {
            if (isset(OperatorHandler::$nameOperators[$this->defaultOperators[$field]]) &&
                    $operatorHandler->getOperator($field, OperatorHandler::OPERATOR_NAME) === false) {
                $field = $field . $this->defaultOperators[$field];
            }
            if (isset(OperatorHandler::$valueOperators[$this->defaultOperators[$field]]) &&
                    $operatorHandler->getOperator($field, OperatorHandler::OPERATOR_VALUE) === false) {
                $value = $value . $this->defaultOperators[$field];
            }
        }

        return compact('field', 'value');
    }

    public function createQueryBuilder($entityName, $collectionHandling = array())
    {
        $this->entityName = $entityName;
        $this->collectionHandling = $collectionHandling;

        $queryBuilder = $this->_em->getRepository($this->entityName)->createQueryBuilder($this->alias);

        return $this->applyToQueryBuilder($queryBuilder);
    }

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

    abstract public function toArray();

    abstract public function toExpr();

    abstract public function toParameters();

    abstract public function get($field);
}
