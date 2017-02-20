<?php

namespace TreeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TreeBundle\Entity\Repository\CategoryRepository;

class CategoryType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('parent', 'entity', array(
                    'class' => 'TreeBundle:Category',
                    'query_builder' => function (CategoryRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->where('c.level = 0')                               
                                ->orderBy('c.title', 'ASC');
                    },
                    'property' => 'title',
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
