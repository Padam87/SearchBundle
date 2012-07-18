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
		public function getParameter($name, $value, $counter = false)
		{
			return array(
				'token' => $this->createToken($name, $counter),
				'value' => $this->cleanOperators($value, $this->valueOperators),
			);
		}
		
		protected function createToken($name, $counter = false)
		{
			$name = $this->cleanOperators($name, $this->nameOperators);
			
			return str_replace('.', '_', $name) . ($counter === false ? '' : $counter);
		}
	}
?>