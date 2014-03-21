<?php

namespace Padam87\SearchBundle\Filter;

interface FilterInterface
{
    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $data
     */
    public function setData($data);

    /**
     * @return string
     */
    public function getEntityName();

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName);

    /**
     * @return string
     */
    public function getAlias();

    /**
     * @param string $alias
     */
    public function setAlias($alias);

    /**
     * @return array
     */
    public function getDefaultOperators();

    /**
     * @param array $defaultOperators
     */
    public function setDefaultOperators($defaultOperators);
} 