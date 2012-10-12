<?php

namespace Padam87\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Padam87\BaseBundle\Entity as Entity;
use Padam87\SearchBundle\Form as Form;

use Padam87\SearchBundle\Filter\FilterFactory;

/**
* @Route("/search")
*/
class DefaultController extends Controller
{
    /**
     * Simple example
     * 
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $product = new Entity\Product();
        $form = $this->createForm(new Form\ProductSearchType(), $product);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $form->bindRequest($request);
        }
        
        $factory = new FilterFactory($this->getDoctrine()->getEntityManager());
        
        $qb = $factory->create($form->getData(), 'p')->createQueryBuilder('Padam87BaseBundle:Product');
        
        $products = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'products' => $products,
            'form' => $form->createView(),
        );
    }
    
    /**
     * Combining two forms to build a query
     * 
     * @Route("/orders")
     * @Template()
     */
    public function ordersAction()
    {
        $order = new Entity\Order();
        $orderForm = $this->createForm(new Form\OrderSearchType(), $order);
        
        $orderItem = new Entity\OrderItem();
        $orderItemForm = $this->createForm(new Form\OrderItemSearchType(), $orderItem);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $orderForm->bindRequest($request);
            $orderItemForm->bindRequest($request);
        }
        
        $factory = new FilterFactory($this->getDoctrine()->getEntityManager());
        
        $qb =
            $factory->create($orderItemForm->getData(), 'i')->applyToQueryBuilder(
                $factory->create($orderForm->getData(), 'o')->createQueryBuilder('Padam87BaseBundle:Order'), 'items'
            );
       
        $orders = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'orders' => $orders,
            'orderForm' => $orderForm->createView(),
            'orderItemForm' => $orderItemForm->createView(),
        );
    }
    
    /**
     * Using collections for filtering directly
     * 
     * @Route("/orders/by-collection")
     * @Template()
     */
    public function ordersByCollectionAction()
    {
        $order = new Entity\Order();
        $order->addItem(new Entity\OrderItem()); // added two items, this should be done on the frontend with js prototype
        $order->addItem(new Entity\OrderItem());
        
        $orderForm = $this->createForm(new Form\OrderSearchType(), $order);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $orderForm->bindRequest($request);
        }
        
        $factory = new FilterFactory($this->getDoctrine()->getEntityManager());
        
        $qb = $factory->create($orderForm->getData(), 'o')->createQueryBuilder('Padam87BaseBundle:Order');
        
        $orders = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'orders' => $orders,
            'orderForm' => $orderForm->createView(),
        );
    }
    
    /**
     * @Route("/orders/by-collection/and")
     * @Template()
     */
    public function ordersByCollectionAndAction()
    {
        $order = new Entity\Order();
        $order->addItem(new Entity\OrderItem());
        $order->addItem(new Entity\OrderItem());
        
        $orderForm = $this->createForm(new Form\OrderSearchType(), $order);
        
        $request = $this->getRequest();

        if('POST' === $request->getMethod()) {
            $orderForm->bindRequest($request);
            
            $order = $orderForm->getData();
        }
        
        $factory = new FilterFactory($this->getDoctrine()->getEntityManager());
        
        $qb = $factory->create($orderForm->getData(), 'o')->createQueryBuilder('Padam87BaseBundle:Order', array(
            'items' => 'AND'
        ));
        
        $orders = $qb->setFirstResult(0)->setMaxResults(100)->getQuery()->getResult();
        
        return array(
            'orders' => $orders,
            'orderForm' => $orderForm->createView(),
        );
    }
}
