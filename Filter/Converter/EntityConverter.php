<?php

namespace Padam87\SearchBundle\Filter\Converter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Padam87\SearchBundle\Filter\Converter\Util\ExprBuilder;
use Padam87\SearchBundle\Filter\Converter\Util\OperatorHandler;
use Padam87\SearchBundle\Filter\Converter\Util\ParameterBuilder;
use Padam87\SearchBundle\Filter\FilterInterface;

class EntityConverter extends AbstractConverter implements ConverterInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(
        ParameterBuilder $parameterBuilder, ExprBuilder $exprBuilder,
        OperatorHandler $operatorHandler, EntityManagerInterface $em
    ) {
        $this->em = $em;

        parent::__construct($parameterBuilder, $exprBuilder, $operatorHandler);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(FilterInterface $filter)
    {
        $fields = $this->em->getClassMetadata(get_class($filter->getData()))->getFieldNames();
        $associations = $this->em->getClassMetadata(get_class($filter->getData()))->getAssociationNames();

        foreach ($associations as $name) {
            if (!$this->em->getClassMetadata(get_class($filter->getData()))->isCollectionValuedAssociation($name)) {
                $fields[] = $name;
            }
        }

        $return = array();

        foreach ($fields as $field) {
            if ($field == 'id') {
                continue;
            }

            $value = $this->get($filter, $field);

            extract($this->processDefaultOperator($filter, $field, $value));

            $return[$field] = $value;

            if (is_object($return[$field]) && method_exists($return[$field], 'getId')) {
                $return[$field] = $return[$field]->getId();
            }
        }

        return $this->filterArray($return);
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
        $getter = "get" . str_replace(" ", "", ucwords(str_replace("_", " ", $field)));

        return $filter->getData()->$getter();
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FilterInterface $filter)
    {
        if (is_object($filter->getData())) {
            return !$this->em->getMetadataFactory()->isTransient(get_class($filter->getData()));
        }

        return false;
    }
}
