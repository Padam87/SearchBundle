<?php

namespace Padam87\SearchBundle\Form;

use Symfony\Component\Form\Form;

use Padam87\SearchBundle\Filter\FilterFactory;

class Filter extends Form
{
    protected $queryBuilder;
    
    protected $em;
    
    public function setQueryBuilder(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $this->em = $this->queryBuilder->getEntityManager();
    }
    
    public function getFilter($alias = NULL)
    {
        if($alias == NULL) {
            $alias = $this->queryBuilder->getRootAlias();
        }
        
        $factory = new FilterFactory($this->em);
        
        return $factory->create($this->getData(), $alias);
    }
    
    public function getQueryBuilder($alias = NULL)
    {
        $filter = $this->getFilter($alias);
        
        if($filter->toExpr() != false) {
            $this->queryBuilder->where($filter->toExpr());
        
            foreach($filter->toParameters() as $parameter) {
                $this->queryBuilder->setParameter($parameter['token'], $parameter['value']);
            }
        }
        
        return $this->queryBuilder;
    }
}