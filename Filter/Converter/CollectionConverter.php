<?php

namespace Padam87\SearchBundle\Filter\Converter;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\Expr;
use Padam87\SearchBundle\Filter\Converter\Util\ExprBuilder;
use Padam87\SearchBundle\Filter\Converter\Util\OperatorHandler;
use Padam87\SearchBundle\Filter\Converter\Util\ParameterBuilder;
use Padam87\SearchBundle\Filter\Filter;
use Padam87\SearchBundle\Filter\FilterInterface;

class CollectionConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @var ConverterManager
     */
    protected $converterManager;

    public function setConverterManager(ConverterManager $converterManager)
    {
        $this->converterManager = $converterManager;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(FilterInterface $filter)
    {
        $data = $filter->getData();
        $return = array();

        foreach ($data->toArray() as $k => $entity) {
            $subfilter = new Filter($entity, null, $filter->getAlias());
            $return[$k] = $this->converterManager->getConverter($subfilter)->toArray($subfilter);
        }

        return array_filter($return, function ($item) {
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
        $sets = array();

        foreach ($this->toArray($filter) as $k => $set) {
            $expressions = array();
            foreach ($set as $name => $value) {
                $expressions[] = $this->exprBuilder->getExpression($filter->getAlias() . '.' . $name, $value, $k);
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
    public function toParameters(FilterInterface $filter)
    {
        $parameters = array();

        foreach ($this->toArray($filter) as $k => $set) {
            foreach ($set as $name => $value) {
                $parameter = $this->parameterBuilder->getParameter($filter->getAlias() . '.' . $name, $value, $k);

                if ($parameter != null) {
                    $parameters[] = $parameter;
                }
            }
        }

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function get(FilterInterface $filter, $field)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterInterface $filter)
    {
        return $filter->getData() instanceof Collection;
    }
}
