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

        if (false !== $operator = $this->getOperator($value, $this->valueOperators)) {
            switch ($operator) {
                case '*':
//						$expression = $Expr->like($name, $this->createToken($name));
//						no ILIKE in doctrine? me likey... NOT

                    $expression = $Expr->lower($name) . " LIKE " . $this->createToken($name, $counter);
                    break;
                case 'NULL':
                    if ('!=' == $this->getOperator($name, $this->nameOperators)) {
                        $expression = $this->cleanOperators($name, $this->nameOperators) . " IS NOT NULL";
                    } else {
                        $expression = $Expr->eq($name, $this->createToken($name, $counter));
                    }
                    break;
            }
        } elseif (false !== $operator = $this->getOperator($name, $this->nameOperators)) {
            $name = $this->cleanOperators($name, $this->nameOperators);
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
            $expression = $Expr->eq($name, $this->createToken($name, $counter));
        }

        return $expression;
    }

    protected function createToken($name, $counter = false)
    {
        $name = $this->cleanOperators($name, $this->nameOperators);

        $token = ':' . str_replace('.', '_', $name) . ($counter === false ? '' : $counter);

        if (in_array($token, $this->tokens)) {
            $token .= '_';
        }

        $this->tokens[] = $token;

        return $token;
    }
}
