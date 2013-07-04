<?php

namespace Padam87\SearchBundle\Filter;

use Padam87\SearchBundle\Filter\ExprBuilder;
use Padam87\SearchBundle\Filter\ParameterBuilder;

use Doctrine\ORM\Query\Expr;

class EntityFilter extends AbstractFilter implements FilterInterface
{
    /**
     * The filter entity
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    public function __construct($filter, $alias)
    {
        $this->entity = $filter;
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $fields = $this->_em->getClassMetadata(get_class($this->entity))->getFieldNames();
        $associations = $this->_em->getClassMetadata(get_class($this->entity))->getAssociationNames();

        foreach ($associations as $name) {
            if (!$this->_em->getClassMetadata(get_class($this->entity))->isCollectionValuedAssociation($name)) {
                $fields[] = $name;
            }
        }

        $filter = array();

        foreach ($fields as $field) {
            if($field == 'id') continue;

            $value = $this->get($field);

            extract($this->processDefaultOperator($field, $value));

            $filter[$field] = $value;

            if (is_object($filter[$field]) && method_exists($filter[$field], 'getId')) {
                $filter[$field] = $filter[$field]->getId();
            }
        }

        return array_filter($filter, function ($item) {
            if($item === false) return true; // boolean field type
            if(empty($item)) return false;

            return true;
        });
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
        $getter = "get" . str_replace(" ", "", ucwords(str_replace("_", " ", $field)));

        return $this->entity->$getter();
    }

    /**
     * {@inheritdoc}
     */
    public function isMultipleLevel()
    {
        return false;
    }
}
