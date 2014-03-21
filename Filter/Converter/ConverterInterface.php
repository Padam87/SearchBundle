<?php

namespace Padam87\SearchBundle\Filter\Converter;

use Padam87\SearchBundle\Filter\FilterInterface;

interface ConverterInterface
{
    /**
     * Converts a filter to array
     *
     * @param FilterInterface $filter
     *
     * @return array
     */
    public function toArray(FilterInterface $filter);

    /**
     * Converts a filter to DQL Expressions
     *
     * @param FilterInterface $filter
     *
     * @return \Doctrine\ORM\Query\Expr
     */
    public function toExpr(FilterInterface $filter);

    /**
     * Converts a filter to DQL Query Parameters
     *
     * @param FilterInterface $filter
     *
     * @return array
     */
    public function toParameters(FilterInterface $filter);

    /**
     * Gets the filter value for a specific field
     *
     * @param FilterInterface $filter
     * @param string $field
     *
     * @return mixed
     */
    public function get(FilterInterface $filter, $field);

    /**
     * Checks if the converter can handle the given filter
     *
     * @param FilterInterface $filter
     *
     * @return boolean
     */
    public function supports(FilterInterface $filter);
}
