<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DireccionDestinoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('calle','text',array('label'=>'Calle *','attr'=>array('class'=>'form-control')))
            ->add('numExterior','text',array('label'=>'Numero Exterior','attr'=>array('class'=>'form-control')))
            ->add('numInterior','text',array('label'=>'Numero Interior','attr'=>array('class'=>'form-control')))
            ->add('colonia','text',array('label'=>'Colonia *','attr'=>array('class'=>'form-control')))
            ->add('pais','text',array('label'=>'País *','attr'=>array('class'=>'form-control')))
            ->add('estado','text',array('label'=>'Estado o entidad federativa *','attr'=>array('class'=>'form-control')))
            ->add('delegacion','text',array('label'=>'Municipio o Delegación *','attr'=>array('class'=>'form-control')))
            ->add('poblacion','text',array('label'=>'Población *','attr'=>array('class'=>'form-control')))
            ->add('cp','text',array('label'=>'Codigo postal *','attr'=>array('class'=>'form-control')))
            ->add('telefono','text',array('label'=>'Teléfono *','attr'=>array('class'=>'form-control')))
            ->add('celular','text',array('label'=>'Celular ','attr'=>array('class'=>'form-control')))
            ->add('email','text',array('label'=>'E-mail *','attr'=>array('class'=>'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\DireccionDestino'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_direcciondestino';
    }
}
