<?php

namespace Padam87\SearchBundle\Filter;

use Padam87\SearchBundle\Filter\ExprBuilder;
use Padam87\SearchBundle\Filter\ParameterBuilder;

use Doctrine\ORM\Query\Expr;

class ArrayFilter extends AbstractFilter implements FilterInterface
{
    /**
     * The filter array
     *
     * @var array
     */
    protected $array;

    /**
     * {@inheritdoc}
     */
    public function __construct($filter, $alias)
    {
        $this->array = $filter;
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        foreach ($this->array as $field => $value) {
            unset($this->array[$field]);

            extract($this->processDefaultOperator($field, $value));

            $this->array[$field] = $value;
        }

        return $this->array;
    }

    /**
     * {@inheritdoc}
     */
    public function toExpr()
    {
        $ExprBuilder = new ExprBuilder();

        $expressions = array();

        foreach ($this->toArray() as $name => $value) {
            $expressions[] = $ExprBuilder->getExpression($this->alias . '.' . $name, $value);
        }

        if (empty($expressions)) {
            return false;
        }

        return new Expr\Andx($expressions);
    }

    /**
     * {@inheritdoc}
     */
    public function toParameters()
    {
        $ParamterBuilder = new ParameterBuilder();

        $parameters = array();

        foreach ($this->toArray() as $name => $value) {
            if($name == 'TYPE') continue;

            $parameter = $ParamterBuilder->getParameter($this->alias . '.' . $name, $value);

            if($parameter != NULL) $parameters[] = $parameter;
        }

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function get($field)
    {
        return isset($this->array[$field]) ? $this->array[$field] : NULL;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultipleLevel()
    {
        return false;
    }
}
