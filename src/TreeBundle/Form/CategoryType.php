<?php

namespace TreeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('parent', 'entity', array(
                    'class' => 'TreeBundle:Category',
                    'property' => 'title',
                    'expanded' => true,
                    'multiple' => false,
                    'required' => false                    
                ))
                ->add('title')                
                ->add('save', 'submit')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'TreeBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'treebundle_category';
    }

}
