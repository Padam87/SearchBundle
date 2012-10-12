<?php

namespace Padam87\SearchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('buyerName', 'text', array(
                'required' => false
            ))
            ->add('items', 'collection', array(
                'type'   => new OrderItemSearchType(),
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
        return array('data_class' => 'Padam87\BaseBundle\Entity\Order');
    }

    public function getName()
    {
        return 'customer';
    }
}
