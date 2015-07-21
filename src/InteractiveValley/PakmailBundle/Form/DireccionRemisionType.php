<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DireccionRemisionType extends AbstractType
{
    private $listaCuidades;
    public function __construct($listaCuidades = array()) {
        $this->listaCuidades = $listaCuidades;
        
        if(!in_array('Mexico', $this->listaCuidades)){
            $this->listaCuidades['Mexico'] = 'Mexico';
        }
        if(!in_array('Estados Unidos', $this->listaCuidades)){
            $this->listaCuidades['Estados Unidos'] = 'Estados Unidos';
        }
        if(!in_array('Canada', $this->listaCuidades)){
            $this->listaCuidades['Canada'] = 'Canada';
        }
        
        sort($this->listaCuidades, SORT_STRING);
        
        array_push($this->listaCuidades, 'Otro...');
        
    }
    
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
            //->add('colonia','text',array('label'=>'Colonia *','attr'=>array('class'=>'form-control')))
            ->add('colonia','hidden')
            ->add('pais','choice',array(
                'label'=>'País *',
                'attr'=>array('class'=>'form-control'),
                'choices'=>$this->listaCuidades,
                ))
            ->add('estado','text',array('label'=>'Estado o entidad federativa *','attr'=>array('class'=>'form-control')))
            ->add('delegacion','text',array('label'=>'Municipio o Delegación *','attr'=>array('class'=>'form-control')))
            ->add('poblacion','text',array('label'=>'Población o Colonia *','attr'=>array('class'=>'form-control')))
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
