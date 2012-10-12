<?php

namespace Padam87\SearchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => false))
            ->add('description', 'text', array('required' => false))
            ->add('price', 'text', array('required' => false))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Padam87\BaseBundle\Entity\Product');
    }

    public function getName()
    {
        return 'product';
    }
}
