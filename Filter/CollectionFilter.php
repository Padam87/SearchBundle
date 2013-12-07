<?php

namespace Padam87\SearchBundle\Filter;

use Padam87\SearchBundle\Filter\ExprBuilder;
use Padam87\SearchBundle\Filter\ParameterBuilder;

use Doctrine\ORM\Query\Expr;

class CollectionFilter extends AbstractFilter implements FilterInterface
{
    /**
     * The filter collection
     *
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $collection;

    /**
     * {@inheritdoc}
     */
    public function __construct($filter, $alias)
    {
        $this->collection = $filter;
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $filter = array();

        $FilterFactory = new FilterFactory($this->em);

        foreach ($this->collection->toArray() as $k => $entity) {
            $filter[$k] = $FilterFactory->create($entity, $this->alias)->toArray();
        }

        return array_filter($filter, function ($item) {
            if(empty($item)) {
                return false;
            }

            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function toExpr()
    {
        $ExprBuilder = new ExprBuilder();

        $sets = array();

        foreach ($this->toArray() as $k => $set) {
            $expressions = array();
            foreach ($set as $name => $value) {
                $expressions[] = $ExprBuilder->getExpression($this->alias . '.' . $name, $value, $k);
            }
            $sets[] = new Expr\Andx($expressions);
        }

        if (empty($sets)) {
            return false;
        }

        return new Expr\Orx($sets);
    }

    /**
     * {@inheritdoc}
     */
    public function toParameters()
    {
        $ParameterBuilder = new ParameterBuilder();

        $parameters = array();

        foreach ($this->toArray() as $k => $set) {
            foreach ($set as $name => $value) {
                $parameter = $ParameterBuilder->getParameter($this->alias . '.' . $name, $value, $k);

                if($parameter != null) {
                    $parameters[] = $parameter;
                }
            }
        }

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function get($field)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultipleLevel()
    {
        return true;
    }
}
