<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaqueteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('referencia','text',array('attr'=>array('class'=>'form-control')))
            ->add('tipo','text',array('attr'=>array('class'=>'form-control')))
            ->add('kilogramos','text',array('attr'=>array('class'=>'form-control')))
            ->add('precio','text',array('attr'=>array('class'=>'form-control')))
            ->add('numGuia','text',array('attr'=>array('class'=>'form-control')))
            ->add('folio','text',array('attr'=>array('class'=>'form-control')))
            ->add('asegurarEnvio','text',array('attr'=>array('class'=>'form-control')))
            ->add('montoSeguro','text',array('attr'=>array('class'=>'form-control')))
            ->add('importeSeguro','text',array('attr'=>array('class'=>'form-control')))
            ->add('observaciones','textarea',array('attr'=>array('class'=>'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Paquete'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_paquete';
    }
}
