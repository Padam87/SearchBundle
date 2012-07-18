<?php

namespace Padam87\SearchBundle\Form;

use
    Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder
;

class CustomerSearchType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => false))
            ->add('street', 'text', array('required' => false))
            ->add('city', 'text', array('required' => false))
            ->add('phone', 'text', array('required' => false))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Acme\PizzaBundle\Entity\Customer');
    }

    public function getName()
    {
        return 'customer';
    }
}
