<?php

namespace Padam87\SearchBundle\Filter;

interface FilterInterface
{
    /**
     * @param mixed $filter
     * @param string $alias
     */
    function __construct($filter, $alias);

    /**
     * Converts a filter to array
     *
     * @return array
     */
    function toArray();

    /**
     * Converts a filter to DQL Expressions
     *
     * @return array
     */
    function toExpr();

    /**
     * Converts a filter to DQL Query Parameters
     *
     * @return array
     */
    function toParameters();

    /**
     * Gets the filter value for a specific field
     *
     * @return mixed
     */
    function get($field);

    /**
     * @return boolean
     */
    function isMultipleLevel();
}