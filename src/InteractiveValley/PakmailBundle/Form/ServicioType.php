<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServicioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label'=>'Servicio','attr'=>array('class'=>'form-control')))
            ->add('imagen',null,array('label'=>'Imagen','attr'=>['class'=>'form-control']))
            ->add('position','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Servicio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_psjeronimobundle_servicio';
    }
}
