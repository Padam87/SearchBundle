<?php
namespace Padam87\SearchBundle\Filter;

use Doctrine\ORM\Query\Expr;

/**
 * Expression Builder
 *
 * @author Adam Prager <adam.prager@netlife.hu>
 */
class ExprBuilder extends OperatorHandler
{
    /**
     * List of created tokens
     *
     * @var array
     */
    private $tokens = array();

    public function getExpression($name, $value, $counter = false)
    {
        $Expr = new Expr();

        if (false !== $operator = $this->getOperator($value, self::OPERATOR_VALUE)) {
            switch ($operator) {
                case '*':
                    $expression = $Expr->lower($name) . " LIKE " . $this->createToken($name, $counter);
                    break;
                case 'NULL':
                    if ('!=' == $this->getOperator($name, self::OPERATOR_VALUE)) {
                        $expression = $this->cleanOperators($name, self::OPERATOR_VALUE) . " IS NOT NULL";
                    } else {
                        $expression = $Expr->eq($name, $this->createToken($name, $counter));
                    }
                    break;
            }
        } elseif (false !== $operator = $this->getOperator($name, self::OPERATOR_NAME)) {
            $name = $this->cleanOperators($name, self::OPERATOR_NAME);
            switch ($operator) {
                case '>=':
                    $expression = $Expr->gte($name, $this->createToken($name, $counter));
                    break;
                case '>':
                    $expression = $Expr->gt($name, $this->createToken($name, $counter));
                    break;
                case '<=':
                    $expression = $Expr->lte($name, $this->createToken($name, $counter));
                    break;
                case '<':
                    $expression = $Expr->lt($name, $this->createToken($name, $counter));
                    break;
                case '!=':
                    $expression = $Expr->neq($name, $this->createToken($name, $counter));
                    break;
                case 'TYPE':
                    $alias = explode('.', $name);
                    $expression = $alias[0] . " INSTANCE OF " . $value;
                    break;
            }
        } else {
            if (is_array($value)) {
                $expression = $Expr->in($name, $this->createToken($name, $counter));
            } else {
                $expression = $Expr->eq($name, $this->createToken($name, $counter));
            }
        }

        return $expression;
    }

    protected function createToken($name, $counter = false)
    {
        $name = $this->cleanOperators($name, self::OPERATOR_NAME);

        $token = ':' . str_replace('.', '_', $name) . ($counter === false ? '' : $counter);

        if (in_array($token, $this->tokens)) {
            $token .= '_';
        }

        $this->tokens[] = $token;

        return $token;
    }
}
