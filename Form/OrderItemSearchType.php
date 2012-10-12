<?php

namespace Padam87\SearchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderItemSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', 'entity', array(
                'class' => 'Padam87\BaseBundle\Entity\Product',
                'required' => false
            ))
            ->add('quantity', 'text', array(
                'required' => false
            ))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Padam87\BaseBundle\Entity\OrderItem');
    }

    public function getName()
    {
        return 'customer';
    }
}