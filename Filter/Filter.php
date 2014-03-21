<?php

namespace Padam87\SearchBundle\Filter;

class Filter implements FilterInterface
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * Entity class name
     *
     * @var string
     */
    protected $entityName;

    /**
     * Alias for the query entity
     *
     * @var string
     */
    protected $alias;

    /**
     * Specifies the search method for a field, defaults to "=" if not specified
     *
     * @var array
     */
    protected $defaultOperators = array();

    /**
     * @param string $data
     * @param string $entityName
     * @param string $alias
     * @param array  $defaultOperators
     */
    public function __construct($data, $entityName, $alias, array $defaultOperators = array())
    {
        $this->data             = $data;
        $this->entityName       = $entityName;
        $this->alias            = $alias;
        $this->defaultOperators = $defaultOperators;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOperators()
    {
        return $this->defaultOperators;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOperators($defaultOperators)
    {
        $this->defaultOperators = $defaultOperators;
    }
}