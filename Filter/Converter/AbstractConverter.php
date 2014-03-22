<?php

namespace Padam87\SearchBundle\Filter\Converter;

use Padam87\SearchBundle\Filter\Converter\Util\ExprBuilder;
use Padam87\SearchBundle\Filter\Converter\Util\OperatorHandler;
use Padam87\SearchBundle\Filter\Converter\Util\ParameterBuilder;
use Padam87\SearchBundle\Filter\FilterInterface;

abstract class AbstractConverter implements ConverterInterface
{
    /**
     * @var Util\ParameterBuilder
     */
    protected $parameterBuilder;

    /**
     * @var Util\ExprBuilder
     */
    protected $exprBuilder;

    /**
     * @var Util\OperatorHandler
     */
    protected $operatorHandler;

    public function __construct(
        ParameterBuilder $parameterBuilder, ExprBuilder $exprBuilder, OperatorHandler $operatorHandler
    ) {
        $this->parameterBuilder = $parameterBuilder;
        $this->exprBuilder      = $exprBuilder;
        $this->operatorHandler  = $operatorHandler;
    }

    /**
     * Finds the operator for a field
     *
     * @param FilterInterface $filter
     * @param string $field
     * @param string $value
     *
     * @return array
     */
    protected function processDefaultOperator(FilterInterface $filter, $field, $value)
    {
        $defaults = $filter->getDefaultOperators();

        if (isset($defaults[$field])) {
            if (isset(OperatorHandler::$nameOperators[$defaults[$field]])
                && $this->operatorHandler->getOperator($field, OperatorHandler::OPERATOR_NAME) === false) {

                $field = $field . $defaults[$field];
            } elseif (isset(OperatorHandler::$valueOperators[$defaults[$field]])
                && $this->operatorHandler->getOperator($field, OperatorHandler::OPERATOR_VALUE) === false) {

                $value = $value . $defaults[$field];
            }
        }

        return compact('field', 'value');
    }

    /**
     * Removes empty array values
     *
     * @param array $array
     *
     * @return array
     */
    protected function filterArray($array)
    {
        return array_filter($array, function ($item) {
            if ($item === false) {
                return true;
            }
            if (empty($item)) {
                return false;
            }

            return true;
        });
    }
}
