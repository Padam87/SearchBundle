<?php

namespace Padam87\SearchBundle\Filter;

abstract class OperatorHandler
{
    protected $valueOperators = array(
        '*'        => '%',
        'NULL'    => '',
    );

    protected $nameOperators = array(
        '>='    => '',
        '>'        => '',
        '<='    => '',
        '<'        => '',
        '!='    => '',
        'TYPE'    => 'TYPE',
    );

    protected function getOperator($value, $operators)
    {
        foreach ($operators as $operator => $replace) {
            if (is_string($value) && strstr($value, $operator)) {
                return $operator;
            }
        }

        return false;
    }

    protected function cleanOperators($value, array $operators)
    {
        if(is_string($value) && strstr($value, "*") !== false) $value = strtolower($value);

        foreach ($operators as $operator => $replace) {
            if(is_string($value)) $value = str_replace($operator, $replace, $value);
        }

        return $value;
    }
}
