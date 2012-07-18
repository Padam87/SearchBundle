<?php

namespace Padam87\SearchBundle\Form;

use
    Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder
;

class OrderSearchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('customer', 'entity', array(
                'class' => 'Acme\PizzaBundle\Entity\Customer',
                'required' => false
            ))
            ->add('items', 'collection', array(
                'type'   => new \Acme\PizzaBundle\Form\Type\OrderItemType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'required'  => false,
                'options'  => array(
                    'required'  => false,
                ),
            ))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Acme\PizzaBundle\Entity\Order');
    }

    public function getName()
    {
        return 'customer';
    }
}
