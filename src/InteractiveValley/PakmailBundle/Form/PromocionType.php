<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PromocionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label'=>'Titulo (opcional)','attr'=>array('class'=>'form-control')))
            ->add('inicio', 'date', array(
                    'attr' => array('class' => 'form-control datepicker'),
                    'widget' => 'single_text',
                    'format' => 'y-MM-dd'
                ))
            ->add('fin', 'date', array(
                    'attr' => array('class' => 'form-control datepicker'),
                    'widget' => 'single_text',
                    'format' => 'y-MM-dd'
                ))
            ->add('file',null,array('label'=>'Imagen','attr'=>['class'=>'form-control']))
            ->add('position','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Promocion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_promocion';
    }
}
