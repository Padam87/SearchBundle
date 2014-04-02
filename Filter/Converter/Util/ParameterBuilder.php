<?php

namespace Padam87\SearchBundle\Filter\Converter\Util;

class ParameterBuilder extends OperatorHandler
{
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
            return null;
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
     * @param mixed  $counter
     *
     * @return string
     */
    protected function createToken($name, $counter = false)
    {
        $name = $this->cleanOperators($name, self::OPERATOR_NAME);

        $token = str_replace('.', '_', $name) . ($counter === false ? '' : $counter);

        return $token;
    }
}
