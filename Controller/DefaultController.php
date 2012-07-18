<?php

namespace Padam87\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Acme\PizzaBundle\Entity as Entity;
use Padam87\SearchBundle\Form as Form;

use Padam87\SearchBundle\Filter\FilterFactory;

/**
* @Route("/search")
*/
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $Customer = new Entity\Customer();
        $form = $this->createForm(new Form\CustomerSearchType(), $Customer);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            
            $Customer = $form->getData();
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('AcmePizzaBundle:Customer')->createQueryBuilder('c');
        
        $factory = new FilterFactory($em);
        
        $filter = $factory->create($Customer, 'c');
        if($filter->toExpr() != false) {
            $qb->where($filter->toExpr());
        
            foreach($filter->toParameters() as $parameter) {
                $qb->setParameter($parameter['token'], $parameter['value']);
            }
        }
        
        $customers = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'customers' => $customers,
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/orders")
     * @Template()
     */
    public function ordersAction()
    {
        $Order = new Entity\Order();
        $Customer = new Entity\Customer();
        $orderForm = $this->createForm(new Form\OrderSearchType(), $Order);
        $customerForm = $this->createForm(new Form\CustomerSearchType(), $Customer);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $orderForm->bindRequest($request);
            $customerForm->bindRequest($request);
            
            $Order = $orderForm->getData();
            $Customer = $customerForm->getData();
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('AcmePizzaBundle:Order')->createQueryBuilder('o');
        
        $factory = new FilterFactory($em);
        
        $orderFilter = $factory->create($Order, 'o');
        if($orderFilter->toExpr() != false) {
            $qb->where($orderFilter->toExpr());
        
            foreach($orderFilter->toParameters() as $parameter) {
                $qb->setParameter($parameter['token'], $parameter['value']);
            }
        }
        
        $customerFilter = $factory->create($Customer, 'c');
        if($customerFilter->toExpr() != false) {
            $qb->join('o.customer', 'c', 'WITH', $customerFilter->toExpr());

            foreach($customerFilter->toParameters() as $parameter) {
                $qb->setParameter($parameter['token'], $parameter['value']);
            }
        }
       
        $orders = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'orders' => $orders,
            'orderForm' => $orderForm->createView(),
            'customerForm' => $customerForm->createView(),
        );
    }
    
    /**
     * @Route("/orders-by-item")
     * @Template()
     */
    public function ordersByItemAction()
    {
        $Order = new Entity\Order();
        $orderForm = $this->createForm(new Form\OrderSearchType(), $Order);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $orderForm->bindRequest($request);
            
            $Order = $orderForm->getData();
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('AcmePizzaBundle:Order')->createQueryBuilder('o');
        
        $factory = new FilterFactory($em);
        
        $orderFilter = $factory->create($Order->getItems(), 'i');
        
        if($orderFilter->toExpr() != false) {
            $qb->join('o.items', 'i', 'WITH', $orderFilter->toExpr());
        
            foreach($orderFilter->toParameters() as $parameter) {
                $qb->setParameter($parameter['token'], $parameter['value']);
            }
        }
       
        $orders = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'orders' => $orders,
            'orderForm' => $orderForm->createView(),
        );
    }
    
    /**
     * @Route("/orders-by-item-and")
     * @Template()
     */
    public function ordersByItemAndAction()
    {
        $Order = new Entity\Order();
        $orderForm = $this->createForm(new Form\OrderSearchType(), $Order);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $orderForm->bindRequest($request);
            
            $Order = $orderForm->getData();
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('AcmePizzaBundle:Order')->createQueryBuilder('o');
        
        $factory = new FilterFactory($em);
        
        foreach($Order->getItems() as $k => $Item) {
            $itemFilter = $factory->create($Item, 'i'. $k);

            if($itemFilter->toExpr() != false) {
                $qb->join('o.items', 'i' . $k, 'WITH', $itemFilter->toExpr());

                foreach($itemFilter->toParameters() as $parameter) {
                    $qb->setParameter($parameter['token'], $parameter['value']);
                }
            }
        }
        
        $orders = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'orders' => $orders,
            'orderForm' => $orderForm->createView(),
        );
    }
    
    /**
     * @Route("/orders-by-item-count")
     * @Template()
     */
    public function ordersByItemCountAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->getRepository('AcmePizzaBundle:Order')->createQueryBuilder('o');
        
        $factory = new FilterFactory($em);
        
        $itemFilter = $factory->create(array(
            'count>=' => 2
        ), 'i');
        
        if($itemFilter->toExpr() != false) {
            $qb->join('o.items', 'i', 'WITH', $itemFilter->toExpr());

            foreach($itemFilter->toParameters() as $parameter) {
                $qb->setParameter($parameter['token'], $parameter['value']);
            }
        }
        
        $orders = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'orders' => $orders,
        );
    }
}
