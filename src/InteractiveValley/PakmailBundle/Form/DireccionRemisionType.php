<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DireccionRemisionType extends AbstractType
{
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empresa','text',array('required'=>FALSE,'label'=>'Empresa','attr'=>array('class'=>'form-control required')))
            ->add('nombre','text',array('label'=>'Nombre del remitente','attr'=>array('class'=>'form-control required')))
            ->add('calle','text',array('label'=>'Calle','attr'=>array('class'=>'form-control required')))
            ->add('numExterior','text',array('label'=>'Número Exterior','attr'=>array('class'=>'form-control required')))
            ->add('numInterior','text',array('label'=>'Número Interior','attr'=>array('class'=>'form-control required')))
            ->add('pais','text',array('label'=>'Número Interior','attr'=>array('class'=>'form-control required')))
            ->add('estado','text',array('label'=>'Estado o entidad federativa','attr'=>array('class'=>'form-control required')))
            ->add('delegacion','text',array('label'=>'Municipio o Delegación','attr'=>array('class'=>'form-control required')))
            ->add('poblacion','text',array('label'=>'Población o Colonia','attr'=>array('class'=>'form-control required')))
            ->add('cp','text',array('label'=>'Código postal','attr'=>array('class'=>'form-control required')))
            ->add('telefono','text',array('label'=>'Teléfono','attr'=>array('class'=>'form-control required input-mask-phone')))
            ->add('celular','text',array('label'=>'Celular ','required'=>false,'attr'=>array('class'=>'form-control input-mask-phone')))
            ->add('email','text',array('label'=>'E-mail','attr'=>array('class'=>'form-control required')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\DireccionRemision'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_direccionremision';
    }
}
