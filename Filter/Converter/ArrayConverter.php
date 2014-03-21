<?php

namespace Padam87\SearchBundle\Filter\Converter;

use Doctrine\ORM\Query\Expr;
use Padam87\SearchBundle\Filter\FilterInterface;

class ArrayConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function toArray(FilterInterface $filter)
    {
        $data = $filter->getData();

        foreach ($data as $field => $value) {
            unset($data[$field]);

            extract($this->processDefaultOperator($filter, $field, $value));

            $data[$field] = $value;
        }

        return array_filter($data, function ($item) {
            if ($item === false) {
                return true;
            }
            if (empty($item)) {
                return false;
            }

            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function toExpr(FilterInterface $filter)
    {
        $expressions = array();

        foreach ($this->toArray($filter) as $name => $value) {
            $expressions[] = $this->exprBuilder->getExpression($filter->getAlias() . '.' . $name, $value);
        }

        if (empty($expressions)) {
            return false;
        }

        return new Expr\Andx($expressions);
    }

    /**
     * {@inheritdoc}
     */
    public function toParameters(FilterInterface $filter)
    {
        $parameters = array();

        foreach ($this->toArray($filter) as $name => $value) {
            if ($name == 'TYPE') {
                continue;
            }

            $parameter = $this->parameterBuilder->getParameter($filter->getAlias() . '.' . $name, $value);

            if ($parameter != null) {
                $parameters[] = $parameter;
            }
        }

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function get(FilterInterface $filter, $field)
    {
        $data = $filter->getData();

        return isset($data[$field]) ? $data[$field] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterInterface $filter)
    {
        return is_array($filter->getData());
    }
}
