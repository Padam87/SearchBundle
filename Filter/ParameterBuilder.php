<?php
	namespace Padam87\SearchBundle\Filter;
	
	use Doctrine\ORM\Query\Expr;
	
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
            if('NULL' == $this->getOperator($value, $this->valueOperators) && '!=' == $this->getOperator($name, $this->nameOperators)) {
                return NULL;
            }
            
			return array(
				'token' => $this->createToken($name, $counter),
				'value' => $this->cleanOperators($value, $this->valueOperators) === false
                            ? 0 // PDO casts bool false to an empty text "" for some reason, this is a workaround
                            : $this->cleanOperators($value, $this->valueOperators),
			);
		}
		
		protected function createToken($name, $counter = false)
		{
			$name = $this->cleanOperators($name, $this->nameOperators);
			
			$token = str_replace('.', '_', $name) . ($counter === false ? '' : $counter);
            
            if(in_array($token, $this->tokens)) {
                $token .= '_';
            }
            
            $this->tokens[] = $token;
            
            return $token;
		}
	}
?>