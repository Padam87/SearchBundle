<?php
    namespace Padam87\SearchBundle\Filter;
    
    use Doctrine\ORM\EntityManager;
    
    abstract class AbstractFilter
    {
        protected $_em;
        
        public function __construct(EntityManager $em = null)
        {
            $this->setEntityManager($em);
        }
        
        public function setEntityManager(EntityManager $em = null)
        {
            $this->_em = $em;
        }
        
        public function isMultipleLevel()
        {
            return false;
        }
        
        abstract public function toArray();
        
        abstract public function toExpr();
        
        abstract public function toParameters();
    }
?>