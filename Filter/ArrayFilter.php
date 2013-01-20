<?php
    namespace Padam87\SearchBundle\Filter;

    use Padam87\SearchBundle\Filter\ExprBuilder;
    use Padam87\SearchBundle\Filter\ParameterBuilder;

    use Doctrine\ORM\Query\Expr;
    use Doctrine\ORM\EntityManager;

    class ArrayFilter extends AbstractFilter
    {
        protected $array;

        public function __construct(EntityManager $em, $entity, $alias)
        {
            parent::__construct($em);

            $this->array = $entity;
            $this->alias = $alias;
        }

        public function toArray()
        {
            return array_filter($this->array, function ($item) {
                if(empty($item)) return false;

                return true;
            });
        }

        public function toExpr()
        {
            $ExprBuilder = new ExprBuilder();

            $expressions = array();

            foreach ($this->toArray() as $name => $value) {
                $expressions[] = $ExprBuilder->getExpression($this->alias . '.' . $name, $value);
            }

            if (empty($expressions)) {
                return false;
            }

            return new Expr\Andx($expressions);
        }

        public function toParameters()
        {
            $ParamterBuilder = new ParameterBuilder();

            $parameters = array();

            foreach ($this->toArray() as $name => $value) {
                if($name == 'TYPE') continue;

                $parameter = $ParamterBuilder->getParameter($this->alias . '.' . $name, $value);

                if($parameter != NULL) $parameters[] = $parameter;
            }

            return $parameters;
        }

        public function get($field)
        {
            return isset($this->array[$field]) ? $this->array[$field] : NULL;
        }
    }
