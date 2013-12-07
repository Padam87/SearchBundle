<?php

namespace Padam87\SearchBundle\Filter;

interface FilterInterface
{
    /**
     * @param mixed $filter
     * @param string $alias
     */
    public function __construct($filter, $alias);

    /**
     * Converts a filter to array
     *
     * @return array
     */
    public function toArray();

    /**
     * Converts a filter to DQL Expressions
     *
     * @return \Doctrine\ORM\Query\Expr
     */
    public function toExpr();

    /**
     * Converts a filter to DQL Query Parameters
     *
     * @return array
     */
    public function toParameters();

    /**
     * Gets the filter value for a specific field
     *
     * @param $field
     *
     * @return mixed
     */
    public function get($field);

    /**
     * @return boolean
     */
    public function isMultipleLevel();
}