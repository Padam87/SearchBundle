<?php

namespace Padam87\SearchBundle\Filter;

class ParameterBuilder extends OperatorHandler
{
    /**
     * List of created tokens
     *
     * @var array
     */
    private $tokens = array();
    
    /**
     * Creates the parameter for the DQL Query
     *
     * @param string $name
     * @param mixed $value
     * @param mixed $counter
     * @return array
     */
    public function getParameter($name, $value, $counter = false)
    {
        // No need to bound parameter to IS NULL or IS NOT NULL expression
        if ('NULL' == $this->getOperator($value, self::OPERATOR_VALUE)) {
            return NULL;
        }

        return array(
            'token' => $this->createToken($name, $counter),
            'value' => $this->cleanOperators($value, self::OPERATOR_VALUE) === false
                        ? 0 // PDO casts bool false to an empty text "" for some reason, this is a workaround
                        : $this->cleanOperators($value, self::OPERATOR_VALUE),
        );
    }

    /**
     * Creates the token for a parameter
     *
     * @param string $name
     * @param mixed $counter
     * @return sting
     */
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
