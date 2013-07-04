<?php

namespace Padam87\SearchBundle\Filter;

class OperatorHandler
{
    const OPERATOR_VALUE    = 1;
    const OPERATOR_NAME     = 2;
    const OPERATOR_ALL      = 3;
    
    /**
     * Operators applied to the fields value
     *
     * @var array
     */
    public static $valueOperators = array(
        '*'     => '%',
        'NULL'  => '',
    );

    /**
     * Operators applied to the fields name
     *
     * @var array
     */
    public static $nameOperators = array(
        '>='    => '',
        '>'     => '',
        '<='    => '',
        '<'     => '',
        '!='    => '',
        'TYPE'  => 'TYPE',
    );

    /**
     * Checks the given string for operators
     *
     * @param string $value
     * @param array $operatorSet
     * @return mixed
     */
    public function getOperator($value, $operatorSet = self::OPERATOR_ALL)
    {
        $operators = $this->getOperators($operatorSet);

        foreach ($operators as $operator => $replace) {
            if (is_string($value) && strstr($value, $operator)) {
                return $operator;
            }
        }

        return false;
    }

    /**
     * Cleans the given string of operators
     *
     * @param string $value
     * @param array $operatorSet
     * @return mixed
     */
    public function cleanOperators($value, $operatorSet = self::OPERATOR_ALL)
    {
        $operators = $this->getOperators($operatorSet);

        if(is_string($value) && strstr($value, "*") !== false) $value = strtolower($value);

        foreach ($operators as $operator => $replace) {
            if(is_string($value)) $value = str_replace($operator, $replace, $value);
        }

        return $value;
    }

    /**
     * Finds a set of operators
     *
     * @param array $operatorSet
     * @return array
     */
    private function getOperators($operatorSet = self::OPERATOR_ALL)
    {
        $operators = array();

        switch ($operatorSet) {
            case self::OPERATOR_VALUE:
                $operators = self::$valueOperators;
                break;
            case self::OPERATOR_NAME:
                $operators = self::$nameOperators;
                break;
            case self::OPERATOR_ALL:
                $operators = array_merge(self::$nameOperators, self::$valueOperators);
                break;
        }

        return $operators;
    }
}
