<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use InteractiveValley\PakmailBundle\Entity\Cliente;

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email','email',array('attr'=>array('class'=>'form-control')))
            ->add('password','password',array('attr'=>array('class'=>'form-control')))
            ->add('salt','hidden')
            ->add('nombre','text',array('attr'=>array('class'=>'form-control')))
            ->add('tipo','choice',array(
                'label'=>'Tipo',
                'empty_value'=>false,
                'read_only'=> false,
                'choices'=>  Cliente::getArrayTipoGrupo(),
                'preferred_choices'=>  Cliente::getPreferedTipoGrupo(),
                'attr'=>array(
                    'class'=>'validate[required] form-control placeholder',
                    'placeholder'=>'Tipo de cliente',
                    'data-bind'=>'value: tipo'
                )))    
            ->add('empresa','entity',array(
                'class'=> 'PakmailBundle:Empresa',
                'label'=>'Empresa',
                'required'=>true,
                'read_only'=>false,
                'property'=>'nombre',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nombre', 'ASC');
                },
                'attr'=>array(
                    'class'=>'form-control placeholder',
                    'placeholder'=>'Empresa',
                    'data-bind'=>'value: empresa',
                    )
                ))
            ->add('isActive',null,array('label'=>'Activo?','attr'=>array(
                'class'=>'checkbox-inline',
                'placeholder'=>'Es activo',
                'data-bind'=>'value: isActive'
                )))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Cliente'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_cliente';
    }
}
