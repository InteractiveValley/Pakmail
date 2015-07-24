<?php

namespace InteractiveValley\PakmailBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DireccionRemisionFrontendType extends AbstractType
{
    private $listaCuidades;
    public function __construct($listaCuidades = array()) {
        $this->listaCuidades = $listaCuidades;
        
        if(!array_key_exists('Mexico', $this->listaCuidades)){
            $this->listaCuidades = array_merge($this->listaCuidades, array('Mexico'=>'México'));
        }
        
        asort($this->listaCuidades, SORT_REGULAR);
        
        $this->listaCuidades = array_merge($this->listaCuidades, array('Otro'=>'Otro...'));
        
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('calle','text',array('label'=>'Calle *','attr'=>array('class'=>'form-control required')))
            ->add('numExterior','text',array('label'=>'Numero Exterior *','attr'=>array('class'=>'form-control required')))
            ->add('numInterior','text',array('label'=>'Numero Interior *','attr'=>array('class'=>'form-control required')))
            ->add('pais','choice',array(
                'label'=>'País *',
                'attr'=>array('class'=>'form-control'),
                'choices'=>$this->listaCuidades,
                'required'=>true
                ))
            ->add('estado','text',array('label'=>'Estado o entidad federativa *','attr'=>array('class'=>'form-control required')))
            ->add('delegacion','text',array('label'=>'Municipio o Delegación *','attr'=>array('class'=>'form-control required')))
            ->add('poblacion','text',array('label'=>'Población o Colonia *','attr'=>array('class'=>'form-control required')))
            ->add('cp','text',array('label'=>'Codigo postal *','attr'=>array('class'=>'form-control required')))
            ->add('telefono','text',array('label'=>'Teléfono *','attr'=>array('class'=>'form-control required')))
            ->add('celular','text',array('label'=>'Celular ','required'=>false,'attr'=>array('class'=>'form-control')))
            ->add('email','text',array('label'=>'E-mail *','attr'=>array('class'=>'form-control required')))
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
