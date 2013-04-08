<?php

namespace Padam87\SearchBundle\Filter;

/**
 * Expression Builder
 *
 * @author Adam Prager <adam.prager@netlife.hu>
 */
class ParameterBuilder extends OperatorHandler
{
    /**
     * List of created tokens
     *
     * @var array
     */
    private $tokens = array();

    public function getParameter($name, $value, $counter = false)
    {
        // No need to bound parameter to IS NOT NULL expression
        if ('NULL' == $this->getOperator($value, self::OPERATOR_VALUE) && '!=' == $this->getOperator($name, self::OPERATOR_NAME)) {
            return NULL;
        }

        return array(
            'token' => $this->createToken($name, $counter),
            'value' => $this->cleanOperators($value, self::OPERATOR_VALUE) === false
                        ? 0 // PDO casts bool false to an empty text "" for some reason, this is a workaround
                        : $this->cleanOperators($value, self::OPERATOR_VALUE),
        );
    }

    protected function createToken($name, $counter = false)
    {
        $name = $this->cleanOperators($name, self::OPERATOR_NAME);

        $token = str_replace('.', '_', $name) . ($counter === false ? '' : $counter);

        if (in_array($token, $this->tokens)) {
            $token .= '_';
        }

        $this->tokens[] = $token;

        return $token;
    }
}
