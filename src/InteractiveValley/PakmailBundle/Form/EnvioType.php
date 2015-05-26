<?php

namespace InteractiveValley\PakmailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EnvioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('perfil','hidden')
            ->add('hasPerfil',null,array('label'=>'Creado en base a perfil?','attr'=>array(
                    'class'=>'checkbox-inline',
                    'placeholder'=>'Tiene perfil',
                    'data-bind'=>'value: hasPerfil'
                )))
            ->add('status','hidden')
            ->add('fechaSolicitud', 'date', array(
                    'attr' => array('class' => 'form-control datepicker'),
                    'widget' => 'single_text',
                    'format' => 'y-MM-dd'
                ))
            ->add('cliente','entity',array(
                'class'=> 'PakmailBundle:Cliente',
                'label'=>'Cliente',
                'required'=>true,
                'read_only'=>false,
                'property'=>'nombre',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nombre', 'ASC');
                },
                'attr'=>array(
                    'class'=>'form-control placeholder',
                    'placeholder'=>'Cliente',
                    'data-bind'=>'value: cliente',
                    )
                ))
            ->add('usuario')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InteractiveValley\PakmailBundle\Entity\Envio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'interactivevalley_pakmailbundle_envio';
    }
}
